<?php

namespace UtilBundle\Twig;

class AppExtension extends \Twig_Extension {

	public function getFilters() {
		return array(
			new \Twig_SimpleFilter( 'resumen', array( $this, 'resumenFilter' ) ),
		);
	}

	public function resumenFilter( $texto, $cantidadParrafos = 0 ) {

		$array  = explode( "\n", $texto );
		$result = '';
		if ( $cantidadParrafos > 0 ) {
			$i = 0;
			foreach ( $array as $item ) {
				$h = preg_replace( '/(?:\r\n|\r|\n)/', '', $item );
				$j = strip_tags($h);

				if ( $j !== "" ) {
					$result .= $item;
					$i ++;
				}
				if ( $i > $cantidadParrafos ) {
					break;
				}
			}

		} else {
			$result = $array[0];
		}

		return $result;
	}
}