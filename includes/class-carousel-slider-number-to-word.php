<?php

/**
 * Convert a number into English text.
 */
class Carousel_Slider_Number_To_Word {

	/**
	 * Carousel_Slider_Number_To_Word constructor.
	 *
	 * @param null $number
	 */
	public function __construct( $number = null ) {
		if ( is_integer( $number ) ) {
			return $this->convert( $number );
		}
	}

	/**
	 * Convert number into english word
	 *
	 * @param $number
	 *
	 * @return string
	 */
	public function convert( $number ) {
		$split_string = explode( ".", (string) $number );
		$integer      = isset( $split_string[0] ) ? $split_string[0] : null;
		$fraction     = isset( $split_string[1] ) ? $split_string[1] : null;
		// list( $integer, $fraction ) = explode( ".", (string) $number );

		$output = "";

		if ( $integer{0} == "-" ) {
			$output  = "negative ";
			$integer = ltrim( $integer, "-" );
		} else if ( $integer{0} == "+" ) {
			$output  = "positive ";
			$integer = ltrim( $integer, "+" );
		}

		if ( $integer{0} == "0" ) {
			$output .= "zero";
		} else {
			$integer = str_pad( $integer, 36, "0", STR_PAD_LEFT );
			$group   = rtrim( chunk_split( $integer, 3, " " ), " " );
			$groups  = explode( " ", $group );

			$groups2 = array();
			foreach ( $groups as $g ) {
				$groups2[] = $this->convert_three_digit( $g{0}, $g{1}, $g{2} );
			}

			for ( $z = 0; $z < count( $groups2 ); $z ++ ) {
				if ( $groups2[ $z ] != "" ) {
					$output .= $groups2[ $z ] . $this->convert_group( 11 - $z ) . (
						$z < 11
						&& ! array_search( '', array_slice( $groups2, $z + 1, - 1 ) )
						&& $groups2[11] != ''
						&& $groups[11]{0} == '0'
							? " and "
							: ", "
						);
				}
			}

			$output = rtrim( $output, ", " );
		}

		if ( $fraction > 0 ) {
			$output .= " point";
			for ( $i = 0; $i < strlen( $fraction ); $i ++ ) {
				$output .= " " . $this->convert_digit( $fraction{$i} );
			}
		}

		return $output;
	}

	/**
	 * Convert three digits into english word
	 *
	 * @param $digit1
	 * @param $digit2
	 * @param $digit3
	 *
	 * @return string
	 */
	private function convert_three_digit( $digit1, $digit2, $digit3 ) {
		$buffer = "";

		if ( $digit1 == "0" && $digit2 == "0" && $digit3 == "0" ) {
			return "";
		}

		if ( $digit1 != "0" ) {
			$buffer .= $this->convert_digit( $digit1 ) . " hundred";
			if ( $digit2 != "0" || $digit3 != "0" ) {
				$buffer .= " and ";
			}
		}

		if ( $digit2 != "0" ) {
			$buffer .= $this->convert_two_digit( $digit2, $digit3 );
		} else if ( $digit3 != "0" ) {
			$buffer .= $this->convert_digit( $digit3 );
		}

		return $buffer;
	}

	/**
	 * Convert single digit into english word
	 *
	 * @param $digit
	 *
	 * @return string
	 */
	private function convert_digit( $digit ) {
		switch ( $digit ) {
			case "0":
				return "zero";
			case "1":
				return "one";
			case "2":
				return "two";
			case "3":
				return "three";
			case "4":
				return "four";
			case "5":
				return "five";
			case "6":
				return "six";
			case "7":
				return "seven";
			case "8":
				return "eight";
			case "9":
				return "nine";
		}
	}

	/**
	 * Convert two digits into english word
	 *
	 * @param $digit1
	 * @param $digit2
	 *
	 * @return string
	 */
	private function convert_two_digit( $digit1, $digit2 ) {
		if ( $digit2 == "0" ) {
			switch ( $digit1 ) {
				case "1":
					return "ten";
				case "2":
					return "twenty";
				case "3":
					return "thirty";
				case "4":
					return "forty";
				case "5":
					return "fifty";
				case "6":
					return "sixty";
				case "7":
					return "seventy";
				case "8":
					return "eighty";
				case "9":
					return "ninety";
			}
		} else if ( $digit1 == "1" ) {
			switch ( $digit2 ) {
				case "1":
					return "eleven";
				case "2":
					return "twelve";
				case "3":
					return "thirteen";
				case "4":
					return "fourteen";
				case "5":
					return "fifteen";
				case "6":
					return "sixteen";
				case "7":
					return "seventeen";
				case "8":
					return "eighteen";
				case "9":
					return "nineteen";
			}
		} else {
			$temp = $this->convert_digit( $digit2 );
			switch ( $digit1 ) {
				case "2":
					return "twenty-$temp";
				case "3":
					return "thirty-$temp";
				case "4":
					return "forty-$temp";
				case "5":
					return "fifty-$temp";
				case "6":
					return "sixty-$temp";
				case "7":
					return "seventy-$temp";
				case "8":
					return "eighty-$temp";
				case "9":
					return "ninety-$temp";
			}
		}
	}

	/**
	 * Convert group into english word
	 *
	 * @param $index
	 *
	 * @return string
	 */
	private function convert_group( $index ) {
		switch ( $index ) {
			case 11:
				return " decillion";
			case 10:
				return " nonillion";
			case 9:
				return " octillion";
			case 8:
				return " septillion";
			case 7:
				return " sextillion";
			case 6:
				return " quintrillion";
			case 5:
				return " quadrillion";
			case 4:
				return " trillion";
			case 3:
				return " billion";
			case 2:
				return " million";
			case 1:
				return " thousand";
			case 0:
				return "";
		}
	}
}