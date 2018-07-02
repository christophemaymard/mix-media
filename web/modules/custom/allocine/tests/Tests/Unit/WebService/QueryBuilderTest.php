<?php

namespace Drupal\allocine\Tests\Unit\WebService;

use Drupal\allocine\WebService\QueryBuilder;
use Drupal\Tests\UnitTestCase;

/**
 * Represents the unit tests for the {@see Drupal\allocine\WebService\QueryBuilder} class.
 * 
 * @group mix-media
 */
class QueryBuilderTest extends UnitTestCase {
  /**
   * The system under test.
   * @var QueryBuilder
   */
  private $sut;
  
  /**
   * {@inheritDoc}
   */
  protected function setUp() {
    parent::setUp();
    
    $this->sut = new QueryBuilder();
  }
  
  /**
   * Tests that getQueryString() returns an empty string when the class is 
   * instantiated.
   */
  public function testGetQueryStringReturnsEmptyStringWhenInstantiated() {
    self::assertSame($this->sut->getQueryString(), '');
  }
  
  /**
   * Tests that setParameter() stores a single value for a parameter and 
   * appends it to the end of the query string.
   */
  public function testSetParameter() {
    // Sets a value for a parameter.
    self::assertSame($this->sut->setParameter('foo', 'value1'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'foo=value1');
    
    // Updates the value of a parameter.
    self::assertSame($this->sut->setParameter('foo', 'value2'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'foo=value2');
    
    // Sets a value for a parameter and appends it to the end of the query.
    self::assertSame($this->sut->setParameter('bar', 'value3'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'foo=value2&bar=value3');
  }
  
  /**
   * Tests that addParameter() stores multiple values for a parameter and 
   * appends it to the end of the query string.
   */
  public function testAddParameter() {
    // Sets a value for a parameter.
    self::assertSame($this->sut->addParameter('foo', 'value1'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'foo=value1');
    
    // Tests that multiple values in same parameter are separated with , (
    // URL encoded as %2C).
    self::assertSame($this->sut->addParameter('foo', 'value2'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'foo=value1%2Cvalue2');
    
    // Sets a value for a parameter.
    self::assertSame($this->sut->addParameter('bar', 'value3'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'foo=value1%2Cvalue2&bar=value3');
  }
  
  /**
   * Tests that getQueryString() returns a string with all parameters in 
   * order.
   */
  public function testGetQueryStringReturnsOrderedParametersString() {
    // Sets a parameter with a single value.
    $this->sut->setParameter('foo', 'value1');
    self::assertEquals($this->sut->getQueryString(), 'foo=value1');
    
    // Sets a parameter with multiple values and appends it to the end.
    $this->sut->addParameter('bar', 'value2');
    self::assertEquals($this->sut->getQueryString(), 'foo=value1&bar=value2');
    
    // Set a parameter with a single value and appends it to the end.
    $this->sut->setParameter('baz', 'value3');
    self::assertEquals($this->sut->getQueryString(), 'foo=value1&bar=value2&baz=value3');
  }
  
  /**
   * Tests that removeParameter() removes a parameter if present.
   */
  public function testRemoveParameter() {
    self::assertSame($this->sut->removeParameter('foo'), $this->sut);
    
    $this->sut->setParameter('foo', 'value1');
    self::assertSame($this->sut->removeParameter('foo'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), '');
    
    $this->sut->addParameter('bar', 'value2');
    self::assertSame($this->sut->removeParameter('bar'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), '');
  }
  
  /**
   * Tests that setFormat() stores a single value for the 'format' parameter 
   * and appends it to the end of the query string.
   */
  public function testSetFormatStoresFormatParameter() {
    self::assertSame($this->sut->setFormat('foo'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'format=foo');
    
    self::assertSame($this->sut->setFormat('bar'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'format=bar');
  }
  
  /**
   * Tests that setPartner() stores a single value for the 'partner' 
   * parameter and appends it to the end of the query string.
   */
  public function testSetPartnerStoresPartnerParameter() {
    self::assertSame($this->sut->setPartner(1), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'partner=1');
    
    self::assertSame($this->sut->setPartner(2), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'partner=2');
  }
  
  /**
   * Tests that setSed() stores a single value for the 'sed' parameter and 
   * appends it to the end of the query string.
   */
  public function testSetSedStoresSedParameter() {
    self::assertSame($this->sut->setSed('foo'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'sed=foo');
    
    self::assertSame($this->sut->setSed('bar'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'sed=bar');
  }
  
  /**
   * Tests that setSignature() stores a single value for the 'sig' 
   * parameter and appends it to the end of the query string.
   */
  public function testSetSignatureStoresSignatureParameter() {
    self::assertSame($this->sut->setSignature('foo'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'sig=foo');
    
    self::assertSame($this->sut->setSignature('bar'), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'sig=bar');
  }
  
  /**
   * Tests that removeSignature() removes the 'sig' parameter if present.
   */
  public function testRemoveSignature() {
    self::assertSame($this->sut->removeSignature(), $this->sut);
    
    $this->sut->setSignature('foo');
    self::assertSame($this->sut->removeSignature(), $this->sut);
    self::assertEquals($this->sut->getQueryString(), '');
  }
  
  /**
   * Tests that setCode() stores a single value for the 'code' parameter 
   * and appends it to the end of the query string.
   */
  public function testSetCodeStoresCodeParameter() {
    self::assertSame($this->sut->setCode(1), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'code=1');
    
    self::assertSame($this->sut->setCode(2), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'code=2');
  }
  
  /**
   * Tests that setProfileLarge() sets the 'profile' parameter as 'large' 
   * and appends it to the end of the query string.
   */
  public function testSetProfileLargeStoresProfileParameter() {
    self::assertSame($this->sut->setProfileLarge(), $this->sut);
    self::assertEquals($this->sut->getQueryString(), 'profile=large');
  }
}
