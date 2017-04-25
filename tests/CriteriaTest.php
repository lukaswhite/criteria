<?php namespace Lukaswhite\Criteria;

use Lukaswhite\Criteria\Criteria;
use Carbon\Carbon;

class CriteriaTest extends \PHPUnit_Framework_TestCase
{
    public function testAlways( )
    {
        $criteria = new Criteria( );
        $this->assertTrue( $criteria->always( ) );
        $this->assertTrue( $criteria->evaluateClause( 'always' ) );
    }

    public function testNever( )
    {
        $criteria = new Criteria( );
        $this->assertFalse( $criteria->never( ) );
        $this->assertFalse( $criteria->evaluateClause( 'never' ) );
    }

    public function testRandom( )
    {
        $criteria = new Criteria( );
        $this->assertTrue( is_bool( $criteria->random( ) ) );
        $this->assertTrue( is_bool( $criteria->evaluateClause( 'random' ) ) );

        // sometimes() is simply an alias
        $this->assertTrue( is_bool( $criteria->sometimes( ) ) );
        $this->assertTrue( is_bool( $criteria->evaluateClause( 'sometimes' ) ) );
    }

    public function testDays( )
    {
        // Mock Carbon; the 29th March 2017 is a Wednesday
        $knownDate = Carbon::create( 2017, 3, 29, 12 );
        Carbon::setTestNow( $knownDate );

        $criteria = new Criteria( );

        $this->assertTrue( $criteria->days( 3 ) );
        $this->assertTrue( $criteria->days( 'wednesday' ) );
        $this->assertTrue( $criteria->evaluateClause( 'days:wednesday' ) );
        $this->assertTrue( $criteria->days( 3, 4, 5 ) );
        $this->assertTrue( $criteria->days( 'wednesday', 'thursday', 'friday' ) );
        $this->assertTrue( $criteria->evaluateClause( 'days:wednesday,thursday,friday' ) );

        $this->assertFalse( $criteria->days( 5 ) );
        $this->assertFalse( $criteria->days( 'friday' ) );
        $this->assertFalse( $criteria->evaluateClause( 'days:friday' ) );

    }

    public function testMonths( )
    {
        // Mock Carbon; the 29th March 2017 is a Wednesday
        $knownDate = Carbon::create( 2017, 3, 29, 12 );
        Carbon::setTestNow( $knownDate );

        $criteria = new Criteria( );

        $this->assertTrue( $criteria->months( 3 ) );
        $this->assertTrue( $criteria->months( 'march' ) );
        $this->assertTrue( $criteria->months( 'mar' ) );
        $this->assertTrue( $criteria->evaluateClause( 'months:march' ) );
        $this->assertTrue( $criteria->evaluateClause( 'months:mar' ) );
        $this->assertTrue( $criteria->months( 3, 4, 5 ) );
        $this->assertTrue( $criteria->months( 'march', 'april', 'may' ) );
        $this->assertTrue( $criteria->evaluateClause( 'months:march,april,may' ) );
        $this->assertTrue( $criteria->evaluateClause( 'months:mar,apr,may' ) );

        $this->assertFalse( $criteria->months( 12 ) );
        $this->assertFalse( $criteria->months( 'december' ) );
        $this->assertFalse( $criteria->months( 'dec' ) );
        $this->assertFalse( $criteria->evaluateClause( 'months:december' ) );
        $this->assertFalse( $criteria->evaluateClause( 'months:dec' ) );

    }

    public function testWeekday( )
    {
        // Mock Carbon; the 29th March 2017 is a Wednesday
        $knownDate = Carbon::create( 2017, 3, 29, 12 );
        Carbon::setTestNow( $knownDate );

        $criteria = new Criteria();

        $this->assertTrue( $criteria->weekday( ) );
        $this->assertFalse( $criteria->weekend( ) );

    }

    public function testWeekend( )
    {
        // Mock Carbon; the 8th April 2017 is a Saturday
        $knownDate = Carbon::create( 2017, 4, 8, 12 );
        Carbon::setTestNow( $knownDate );

        $criteria = new Criteria();

        $this->assertTrue( $criteria->weekend( ) );
        $this->assertFalse( $criteria->weekday( ) );

    }

    public function testEqual( )
    {
        $criteria = new Criteria( [ 'x' => 10, 'y' => 20 ] );
        $this->assertTrue( $criteria->eq( 'x', 10 )  );
        $this->assertTrue( $criteria->evaluateClause( 'eq:x,10' ) );
        $this->assertTrue( $criteria->eq( 'x', '10' )  );
        $this->assertFalse( $criteria->eq( 'x', 20 ) );
        $this->assertFalse( $criteria->evaluateClause( 'eq:x,20' ) );
    }

    public function testNotEqual( )
    {
        $criteria = new Criteria( [ 'x' => 10, 'y' => 20 ] );
        $this->assertTrue( $criteria->neq( 'x', 9 )  );
        $this->assertTrue( $criteria->evaluateClause( 'neq:x,9' ) );
        $this->assertTrue( $criteria->neq( 'x', '9' )  );
        $this->assertFalse( $criteria->neq( 'x', 10 ) );
        $this->assertFalse( $criteria->evaluateClause( 'neq:x,10' ) );
    }

    public function testExists( )
    {
        $criteria = new Criteria( [ 'x' => 10, 'y' => 20 ] );
        $this->assertTrue( $criteria->exists( 'x' )  );
        $this->assertTrue( $criteria->evaluateClause( 'exists:x' ) );
        $this->assertTrue( $criteria->exists( 'y' )  );
        $this->assertTrue( $criteria->evaluateClause( 'exists:y' ) );
        $this->assertFalse( $criteria->exists( 'z' )  );
        $this->assertFalse( $criteria->evaluateClause( 'exists:z' ) );
    }

    public function testLessThan( )
    {
        $criteria = new Criteria( [ 'x' => 10 ] );
        $this->assertTrue( $criteria->lt( 'x', 20 ) );
        $this->assertTrue( $criteria->evaluateClause( 'lt:x,20' ) );
        $this->assertFalse( $criteria->lt( 'x', 9 ) );
        $this->assertFalse( $criteria->evaluateClause( 'lt:x,9' ) );
    }

    public function testLessThanOrEqualTo( )
    {
        $criteria = new Criteria( [ 'x' => 10 ] );
        $this->assertTrue( $criteria->lte( 'x', 20 ) );
        $this->assertTrue( $criteria->evaluateClause( 'lte:x,20' ) );
        $this->assertTrue( $criteria->lte( 'x', 10 ) );
        $this->assertTrue( $criteria->evaluateClause( 'lte:x,10' ) );
        $this->assertFalse( $criteria->lte( 'x', 9 ) );
        $this->assertFalse( $criteria->evaluateClause( 'lte:x,9' ) );
    }

    public function testGreaterThan( )
    {
        $criteria = new Criteria( [ 'x' => 10 ] );
        $this->assertTrue( $criteria->gt( 'x', 5 ) );
        $this->assertTrue( $criteria->evaluateClause( 'gt:x,5' ) );
        $this->assertFalse( $criteria->gt( 'x', 11 ) );
        $this->assertFalse( $criteria->evaluateClause( 'gt:x,11' ) );
    }

    public function testGreaterThanOrEqualTo( )
    {
        $criteria = new Criteria( [ 'x' => 10 ] );
        $this->assertTrue( $criteria->gte( 'x', 5 ) );
        $this->assertTrue( $criteria->evaluateClause( 'gte:x,5' ) );
        $this->assertTrue( $criteria->gte( 'x', 10 ) );
        $this->assertTrue( $criteria->evaluateClause( 'gte:x,10' ) );
        $this->assertFalse( $criteria->gte( 'x', 11 ) );
        $this->assertFalse( $criteria->evaluateClause( 'gte:x,11' ) );
    }

    public function testEnv( )
    {
        $criteria = new Criteria( );

        // Note that CRITERIATESTENV is set in phpunit.xml
        $this->assertTrue( $criteria->evaluateClause( 'env:CRITERIATESTENV,foobar' ) );
    }

    public function testAnd( )
    {
        // Mock Carbon; the 29th March 2017 is a Wednesday
        $knownDate = Carbon::create( 2017, 3, 29, 12 );
        Carbon::setTestNow( $knownDate );

        $criteria = new Criteria( [ 'x' => 12, 'y' => 10 ] );

        $this->assertTrue( $criteria->evaluate( 'days:monday,tuesday,wednesday&&gte:x,10' ) );
        $this->assertFalse( $criteria->evaluate( 'days:monday,tuesday,wednesday&&gte:y,20' ) );

    }

    public function testOr( )
    {
        // Mock Carbon; the 29th March 2017 is a Wednesday
        $knownDate = Carbon::create( 2017, 3, 29, 12 );
        Carbon::setTestNow( $knownDate );

        $criteria = new Criteria( [ 'x' => 12, 'y' => 10 ] );

        $this->assertTrue( $criteria->evaluate( 'days:monday,tuesday,wednesday||gte:x,10' ) );
        $this->assertTrue( $criteria->evaluate( 'days:monday,tuesday,wednesday||gte:y,20' ) );

        $this->assertFalse( $criteria->evaluate( 'days:thursday,friday||gte:x,20' ) );

    }

    public function testInvalidClause( )
    {
        $criteria = new Criteria( );
        //$this->expectException(InvalidCriteriaClauseException::class); <-- not supported
        //$criteria->evaluateClause( 'foo:bar' );
    }

}
