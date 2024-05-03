<?php
namespace AIOSEO\Plugin\Addon\Redirects\ImportExport;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use AIOSEO\Plugin\Addon\Redirects\Models;
use AIOSEO\Plugin\Addon\Redirects\Utils;

class RankMath extends Importer {
	/**
	 * A list of plugins to look for to import.
	 *
	 * @since 1.2.8
	 *
	 * @var array
	 */
	public $plugins = [
		[
			'name'     => 'Rank Math SEO',
			'version'  => '1.0.96',
			'basename' => 'seo-by-rank-math/rank-math.php',
			'slug'     => 'seo-by-rank-math'
		]
	];

	/**
	 * Import.
	 *
	 * @since 1.2.8
	 *
	 * @return void
	 */
	public function doImport() {
		if ( ! aioseo()->core->db->tableExists( 'rank_math_redirections' ) ) {
			return;
		}

		$rules = aioseo()->core->db->start( 'rank_math_redirections' )
			->run()
			->result();

		foreach ( $rules as $rule ) {
			if ( ! $this->validateStatusCode( $rule->header_code ) ) {
				continue;
			}

			if ( empty( $rule->url_to ) ) {
				$rule->url_to = '/';
			}

			$rule->sources = aioseo()->helpers->maybeUnserialize( $rule->sources );
			// We couldn't decode the sources.
			if ( empty( $rule->sources ) ) {
				continue;
			}

			$rule->url_to = Utils\WpUri::excludeHomeUrl( $rule->url_to );

			// Codes higher than 400 don't have a target URL.
			if ( 400 <= $rule->header_code ) {
				$rule->url_to = '';
			}

			foreach ( $rule->sources as $source ) {
				// Some sources may not have a leading slash, which we require.
				$source['pattern'] = $this->leadingSlashIt( $source['pattern'] );

				$redirect = Models\Redirect::getRedirectBySourceUrl( $source['pattern'] );
				$redirect->set( [
					'source_url'   => $source['pattern'],
					'target_url'   => $rule->url_to,
					'type'         => $rule->header_code,
					'query_param'  => json_decode( aioseoRedirects()->options->redirectDefaults->queryParam )->value,
					'group'        => 'manual',
					'regex'        => 'regex' === $source['comparison'],
					'ignore_slash' => aioseoRedirects()->options->redirectDefaults->ignoreSlash,
					'ignore_case'  => aioseoRedirects()->options->redirectDefaults->ignoreCase,
					'enabled'      => 'active' === $rule->status
				] );

				$redirect->save();

				// Save hits.
				if ( $rule->hits ) {
					$redirect->setHits( (int) $rule->hits );
				}
			}
		}
	}
}