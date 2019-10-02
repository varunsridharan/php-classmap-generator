<?php

use Symfony\Component\Finder\Finder;

/**
 * ClassMapGenerator
 *
 * @author Gyula Sallai <salla016@gmail.com>
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class ClassMapGenerator {
	/**
	 * Checks if the given path is absolute
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public static function is_absolute_path( $path ) {
		return substr( $path, 0, 1 ) === '/' || substr( $path, 1, 1 ) === ':' || substr( $path, 0, 2 ) === '\\\\';
	}

	/**
	 * Normalize a path. This replaces backslashes with slashes, removes ending
	 * slash and collapses redundant separators and up-level references.
	 *
	 * @param string $path Path to the file or directory
	 *
	 * @return string
	 */
	public static function normalize_path( $path ) {
		$parts    = array();
		$path     = strtr( $path, '\\', '/' );
		$prefix   = '';
		$absolute = false;
		// extract a prefix being a protocol://, protocol:, protocol://drive: or simply drive:
		if ( preg_match( '{^( [0-9a-z]{2,}+: (?: // (?: [a-z]: )? )? | [a-z]: )}ix', $path, $match ) ) {
			$prefix = $match[1];
			$path   = substr( $path, strlen( $prefix ) );
		}
		if ( substr( $path, 0, 1 ) === '/' ) {
			$absolute = true;
			$path     = substr( $path, 1 );
		}
		$up = false;
		foreach ( explode( '/', $path ) as $chunk ) {
			if ( '..' === $chunk && ( $absolute || $up ) ) {
				array_pop( $parts );
				$up = ! ( empty( $parts ) || '..' === end( $parts ) );
			} elseif ( '.' !== $chunk && '' !== $chunk ) {
				$parts[] = $chunk;
				$up      = '..' !== $chunk;
			}
		}
		return $prefix . ( $absolute ? '/' : '' ) . implode( '/', $parts );
	}

	/**
	 * Iterate over all files in the given directory searching for classes
	 *
	 * @param \Iterator|string $path The path to search in or an iterator
	 * @param array            $args
	 *
	 * @return array             A class map array
	 * @throws \RuntimeException When the path is neither an existing file nor directory
	 */
	public static function create_map( $path, $args = array() ) {
		$args      = array_merge( array(
			'blacklist'          => null,
			'namespace'          => null,
			'excluded_paths'     => array(),
			'excluded_namespace' => array(),
			'fullpath'           => false,
		), $args );
		$blacklist = $args['blacklist'];
		$namespace = $args['namespace'];

		if ( is_string( $path ) ) {
			if ( is_file( $path ) ) {
				$path = array( new SplFileInfo( $path ) );
			} elseif ( is_dir( $path ) ) {
				$path = Finder::create()
					->files()
					->followLinks()
					->name( '/\.(php|inc|hh)$/' )
					->in( $path )
					->exclude( array_merge( array(
						'vendor',
						'node_modules',
						'assets',
						'.git',
					), $args['excluded_paths'] ) );
			} else {
				throw new RuntimeException( 'Could not scan for classes inside "' . $path . '" which does not appear to be a file nor a folder' );
			}
		}

		$map = array();
		$cwd = realpath( getcwd() );

		foreach ( $path as $file ) {
			$file_path      = $file->getPathname();
			$file_path_base = $file_path;
			if ( ! in_array( pathinfo( $file_path, PATHINFO_EXTENSION ), array( 'php', 'inc', 'hh' ), true ) ) {
				continue;
			}

			if ( ! self::is_absolute_path( $file_path ) ) {
				$file_path = $cwd . '/' . $file_path;
				$file_path = self::normalize_path( $file_path );
			} else {
				$file_path = preg_replace( '{[\\\\/]{2,}}', '/', $file_path );
			}

			if ( $blacklist && preg_match( $blacklist, strtr( realpath( $file_path ), '\\', '/' ) ) ) {
				continue;
			}

			if ( $blacklist && preg_match( $blacklist, strtr( $file_path, '\\', '/' ) ) ) {
				continue;
			}

			$classes = self::find_classes( $file_path );

			foreach ( $classes as $class ) {
				$failed = false;
				if ( ! empty( $namespace ) && 0 !== strpos( $class, $namespace ) ) {
					continue;
				}

				if ( ! empty( $args['excluded_namespace'] ) && is_array( $args['excluded_namespace'] ) ) {
					foreach ( $args['excluded_namespace'] as $nspace ) {
						if ( ! empty( $nspace ) && 0 === strpos( $class, $nspace ) ) {
							$failed = true;
							break;
						}
					}
				}

				if ( false === $failed && ! isset( $map[ $class ] ) ) {
					$map[ $class ] = ( false === $args['fullpath'] ) ? $file_path_base : $file_path;
				}
			}
		}

		return $map;
	}

	/**
	 * Extract the classes in the given file
	 *
	 * @param string $path The file to check
	 *
	 * @return array             The found classes
	 * @throws \RuntimeException
	 */
	private static function find_classes( $path ) {
		$extra_types = PHP_VERSION_ID < 50400 ? '' : '|trait';
		if ( defined( 'HHVM_VERSION' ) && version_compare( HHVM_VERSION, '3.3', '>=' ) ) {
			$extra_types .= '|enum';
		}

		$contents = @php_strip_whitespace( $path );
		if ( ! $contents ) {
			if ( ! file_exists( $path ) ) {
				$message = 'File at "%s" does not exist, check your classmap definitions';
			} elseif ( ! is_readable( $path ) ) {
				$message = 'File at "%s" is not readable, check its permissions';
			} elseif ( '' === trim( file_get_contents( $path ) ) ) {
				return array();
			} else {
				$message = 'File at "%s" could not be parsed as PHP, it may be binary or corrupted';
			}
			$error = error_get_last();
			if ( isset( $error['message'] ) ) {
				$message .= PHP_EOL . 'The following message may be helpful:' . PHP_EOL . $error['message'];
			}
			throw new RuntimeException( sprintf( $message, $path ) );
		}

		if ( ! preg_match( '{\b(?:class|interface' . $extra_types . ')\s}i', $contents ) ) {
			return array();
		}

		$contents = preg_replace( '{<<<[ \t]*([\'"]?)(\w+)\\1(?:\r\n|\n|\r)(?:.*?)(?:\r\n|\n|\r)(?:\s*)\\2(?=\s+|[;,.)])}s', 'null', $contents );
		$contents = preg_replace( '{"[^"\\\\]*+(\\\\.[^"\\\\]*+)*+"|\'[^\'\\\\]*+(\\\\.[^\'\\\\]*+)*+\'}s', 'null', $contents );

		if ( substr( $contents, 0, 2 ) !== '<?' ) {
			$contents = preg_replace( '{^.+?<\?}s', '<?', $contents, 1, $replacements );
			if ( 0 === $replacements ) {
				return array();
			}
		}
		$contents = preg_replace( '{\?>(?:[^<]++|<(?!\?))*+<\?}s', '?><?', $contents );
		$pos      = strrpos( $contents, '?>' );
		if ( false !== $pos && false === strpos( substr( $contents, $pos ), '<?' ) ) {
			$contents = substr( $contents, 0, $pos );
		}
		if ( preg_match( '{(<\?)(?!(php|hh))}i', $contents ) ) {
			$contents = preg_replace( '{//.* | /\*(?:[^*]++|\*(?!/))*\*/}x', '', $contents );
		}

		preg_match_all( '{
            (?:
                 \b(?<![\$:>])(?P<type>class|interface' . $extra_types . ') \s++ (?P<name>[a-zA-Z_\x7f-\xff:][a-zA-Z0-9_\x7f-\xff:\-]*+)
               | \b(?<![\$:>])(?P<ns>namespace) (?P<nsname>\s++[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*+(?:\s*+\\\\\s*+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*+)*+)? \s*+ [\{;]
            )
        }ix', $contents, $matches );

		$classes   = array();
		$namespace = '';

		for ( $i = 0, $len = count( $matches['type'] ); $i < $len; $i++ ) {
			if ( ! empty( $matches['ns'][ $i ] ) ) {
				$namespace = str_replace( array( ' ', "\t", "\r", "\n" ), '', $matches['nsname'][ $i ] ) . '\\';
			} else {
				$name = $matches['name'][ $i ];
				if ( 'extends' === $name || 'implements' === $name ) {
					continue;
				}
				if ( ':' === $name[0] ) {
					$name = 'xhp' . substr( str_replace( array( '-', ':' ), array( '_', '__' ), $name ), 1 );
				} elseif ( 'enum' === $matches['type'][ $i ] ) {
					$name = rtrim( $name, ':' );
				}
				$classes[] = ltrim( $namespace . $name, '\\' );
			}
		}
		return $classes;
	}
}

if ( file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
	require_once __DIR__ . '/../vendor/autoload.php';
} elseif ( file_exists( dirname( dirname( dirname( __DIR__ ) ) ) . '/autoload.php' ) ) {
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/autoload.php';
}

$config_file = ( isset( $argv[1] ) ) ? $argv[1] : false;
if ( ! empty( $config_file ) && file_exists( $config_file ) ) {
	$cgf = file_get_contents( $config_file );
	if ( ! empty( $cgf ) ) {
		$cgf                          = json_decode( $cgf, true );
		$cgf                          = array_merge( array(
			'namespace' => '',
			'source'    => '',
			'output'    => array(),
			'excluded'  => array(),
			'fullpath'  => false,
		), $cgf );
		$cgf['excluded']              = array_merge( array(
			'namespace' => '',
			'paths'     => '',
		), $cgf['excluded'] );
		$cgf['output']                = array_merge( array(
			'location' => '',
			'type'     => '',
		), $cgf['output'] );
		$cgf['source']                = ( ! is_array( $cgf['source'] ) ) ? explode( ',', $cgf['source'] ) : $cgf['source'];
		$cgf['excluded']['namespace'] = ( ! is_array( $cgf['excluded']['namespace'] ) ) ? explode( ',', $cgf['excluded']['namespace'] ) : $cgf['excluded']['namespace'];
		$cgf['excluded']['paths']     = ( ! is_array( $cgf['excluded']['paths'] ) ) ? explode( ',', $cgf['excluded']['paths'] ) : $cgf['excluded']['paths'];
		$maps                         = array();

		foreach ( $cgf['source'] as $path ) {
			$maps = array_merge( $maps, ClassMapGenerator::create_map( $path, array(
				'excluded_paths'     => $cgf['excluded']['paths'],
				'excluded_namespace' => $cgf['excluded']['namespace'],
				'namespace'          => $cgf['namespace'],
				'fullpath'           => $cgf['fullpath'],
			) ) );
		}


		$last_updated = date( 'D d-M-Y / h:i:s:a' );
		$total        = count( $maps );
		$namespace    = $cgf['namespace'];
		$contents     = <<<PHP
<?php
/**
 * Last Updated: $last_updated
 * Total Class:  $total
 * Namespace: $namespace
 */

return %s;

PHP;
		$save_data    = ( 'json' === $cgf['output']['type'] ) ? json_encode( $maps ) : var_export( $maps, true );
		$save_data    = ( 'json' === $cgf['output']['type'] ) ? $save_data : sprintf( $contents, $save_data );
		file_put_contents( $cgf['output']['location'], $save_data );
	} else {
		echo 'Config File Read Error!';
	}
} else {
	echo 'Classmap Config File Not Exists';
}
