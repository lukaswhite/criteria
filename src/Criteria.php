<?php namespace Lukaswhite\Criteria;

use Carbon\Carbon;
use Lukaswhite\Criteria\Exception\InvalidClauseException;

class Criteria
{

    /**
     * Today's date
     *
     * @var Carbon
     */
    private $today;

    /**
     * Data to help with evaluation
     *
     * @var array
     */
    private $data;

    /**
     * Critera constructor.
     *
     * @param array $data An associative array of data
     */
    public function __construct( $data = [ ] )
    {
        $this->data         =   $data;
        $this->today        =   Carbon::now( );
    }

    /**
     * Evaluate one or more clauses. See the comments on the evaluateClause() method
     * for more details.
     *
     * @param string $str
     * @return bool
     */
    public function evaluate( $str )
    {
        // ^([a-zA-Z0-9_:,]*)(&&|\|\|?)([a-zA-Z0-9_:,]*)*
        //$output = preg_split( "/ (&&|\|\|) /", $str );

        if ( preg_match( '/^([a-zA-Z0-9_:,]*)(&&|\|\|?)([a-zA-Z0-9_:,]*)/', $str, $arr ) ) {
            //var_dump( $arr );
            array_shift( $arr );
            return $this->evaluateMultiple( $arr );
        } else {
            return $this->evaluateClause( $str );
        }

    }

    /**
     * Evaluate a clause that's specified as a string
     *
     * Some examples:
     *
     *  ->evaluateClause( 'days:monday,tuesday,wednesday' ) )
     *  ->evaluateClause( 'days:1,2,3' ) )
     *  ->evaluateClause( 'minOffers:10' ) )
     *  ->evaluateClause( 'always' )
     *  ->evaluateClause( 'never' )
     *  ->evaluateClause( 'random' )
     *
     * @param $clause
     * @return mixed
     *
     * @throws InvalidClauseException
     */
    public function evaluateClause( $clause )
    {
        // method:arg1,arg2,arg3
        $parts = explode( ':', $clause );
        $method = $parts[ 0 ];
        $args = ( isset( $parts[ 1 ] ) ) ? explode( ',', $parts[ 1 ] ) : [ ];
        try {
            return call_user_func_array( [ $this, $method ], $args );
        } catch ( \Exception $e ) {
            throw new InvalidClauseException( $e->getMessage( ) );
        }
    }

    /**
     * Always meet the criteria
     *
     * @return bool
     */
    public function always( )
    {
        return true;
    }

    /**
     * Never meet the criteria
     *
     * @return bool
     */
    public function never( )
    {
        return false;
    }

    /**
     * Randomly meet the criteria
     *
     * @return bool
     */
    public function random( )
    {
        return ( bool ) rand( 0, 1 );
    }

    /**
     * Meet the criteria sometimes; essentially an alias to random()
     *
     * @return bool
     */
    public function sometimes( )
    {
        return $this->random( );
    }

    /**
     * Meet the criteria on specific days
     *
     * Usage:
     *  ->days( 1, 2, 3 )  or
     *  ->days( 'monday', 'tuesday', 'wednesday' )
     *
     * @param mixed ...
     * @return bool
     */
    public function days( )
    {
        $days = func_get_args( );

        // If we've been provided with day names, convert them to integers
        $days = array_map( function( $day ) {
            if ( is_numeric( $day ) ) {
                return intval( $day );
            }
            return intval( date('N', strtotime( $day ) ) );
        }, $days );

        // Iterate through them, see if any represent today
        foreach( $days as $day ) {
            if ( $day === $this->today->dayOfWeek ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Meet the criteria on specific months
     *
     * Usage:
     *  ->months( 1, 2, 3 )  or
     *  ->days( 'january', 'february', 'march' )
     *
     * @param mixed ...
     * @return bool
     */
    public function months( )
    {
        $months = func_get_args( );

        // If we've been provided with month names, convert them to integers
        $months = array_map( function( $month ) {
            if ( is_numeric( $month ) ) {
                return intval( $month );
            }
            $date = date_parse( ucfirst( $month ) );
            return $date['month'];
        }, $months );

        // Iterate through them, see if any represent today
        foreach( $months as $month ) {
            if ( $month === $this->today->month ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Meet the criteria on a weekday
     *
     * @return bool
     */
    public function weekday( )
    {
        return $this->today->isWeekday();
    }

    /**
     * Meet the criteria on the weekend
     *
     * @return bool
     */
    public function weekend( )
    {
        return $this->today->isWeekend();
    }

    /**
     * Meets the criteria if the specified key exists in the data
     *
     * @param string $key
     * @return bool
     */
    public function exists( $key )
    {
        return ! empty( $this->data[ $key ] );
    }

    /**
     * Meets the criteria if the data specified by $key is equals to $value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws InvalidClauseException
     */
    public function eq( $key, $value )
    {
        $this->ensureKeyExists( $key );
        return ( $this->data[ $key ] == $value );
    }

    /**
     * Meets the criteria if the data specified by $key does not equal $value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws InvalidClauseException
     */
    public function neq( $key, $value )
    {
        $this->ensureKeyExists( $key );
        return ( $this->data[ $key ] != $value );
    }

    /**
     * Meets the criteria if the value in $key is less than $value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws InvalidClauseException
     */
    public function lt( $key, $value )
    {
        $this->ensureKeyExists( $key );
        return ( $this->data[ $key ] < $value );
    }

    /**
     * Meets the criteria if the value in $key is less than or equal to $value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws InvalidClauseException
     */
    public function lte( $key, $value )
    {
        $this->ensureKeyExists( $key );
        return ( $this->data[ $key ] <= $value );
    }

    /**
     * Meets the criteria if the value in $key is greater than $value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws InvalidClauseException
     */
    public function gt( $key, $value )
    {
        $this->ensureKeyExists( $key );
        return ( $this->data[ $key ] > $value );
    }

    /**
     * Meets the criteria if the value in $key is greater than or equal to $value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws InvalidClauseException
     */
    public function gte( $key, $value )
    {
        $this->ensureKeyExists( $key );
        return ( $this->data[ $key ] >= $value );
    }

    /**
     * Meet the criteria if an environment variable matches one of the provided values
     *
     * e.g.
     *
     *  ->env( 'app_env', 'production' )
     *  ->env( 'app_env', 'production', 'staging' )
     *
     * @param string $value
     * @param mixed ...
     * @return bool
     */
    public function env( $value )
    {
        if ( count( func_get_args( ) ) == 1 ) {
            return false;
        }

        array_shift( func_get_args( ) );

        return in_array( $_ENV[ $value ], func_get_args( ) );
    }

    /**
     * Evaluates multiple clauses, separated by an AND (&&) or OR (||)
     *
     * @param array $arr
     * @return bool
     */
    private function evaluateMultiple( $arr )
    {
        if ( $arr[ 1 ] == '&&' ) {
            return ( $this->evaluateClause( $arr[ 0 ] ) && $this->evaluateClause( $arr[ 2 ] ) );
        } else if ( $arr[ 1 ] == '||' ) {
            return ( $this->evaluateClause( $arr[ 0 ] ) || $this->evaluateClause( $arr[ 2 ] ) );
        }
    }

    /**
     * Ensure that the specified key exists in the current set of data
     *
     * @param string $key
     * @throws InvalidClauseException
     */
    private function ensureKeyExists( $key )
    {
        if ( ! isset( $this->data[ $key ] ) ) {
            throw new InvalidClauseException( sprintf( 'Key %s not provided', $key ) );
        }
    }

}
