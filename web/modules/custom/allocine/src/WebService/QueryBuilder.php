<?php

namespace Drupal\allocine\WebService;

/**
 * Represents a builder of parameters in the query of the web service URIs.
 */
class QueryBuilder {
  /**
   * The list of parameters.
   * @var array
   */
  private $params = [];
  
  /**
   * Sets the value of the 'format' parameter.
   * 
   * @param   string  $value  The value to set.
   * @return  QueryBuilder  This instance.
   */
  public function setFormat($value) {
    return $this->setParameter('format', $value);
  }
  
  /**
   * Sets the value of the 'partner' parameter.
   * 
   * @param   string  $value  The value to set.
   * @return  QueryBuilder  This instance.
   */
  public function setPartner($value) {
    return $this->setParameter('partner', $value);
  }
  
  /**
   * Sets the value of the 'sed' parameter.
   * 
   * @param   string  $value  The value to set.
   * @return  QueryBuilder  This instance.
   */
  public function setSed($value) {
    return $this->setParameter('sed', $value);
  }
  
  /**
   * Sets the value of the 'sig' parameter.
   * 
   * @param   string  $value  The value to set.
   * @return  QueryBuilder  This instance.
   */
  public function setSignature($value) {
    return $this->setParameter('sig', $value);
  }
  
  /**
   * Removes the 'sig' parameter if present.
   * 
   * @return  QueryBuilder  This instance.
   */
  public function removeSignature() {
    return $this->removeParameter('sig');
  }
  
  /**
   * Sets the value of the 'code' parameter.
   * 
   * @param   int $value  The code to set.
   * @return  QueryBuilder  This instance.
   */
  public function setCode($value) {
    return $this->setParameter('code', $value);
  }
  
  /**
   * Sets the value of the 'profile' parameter as 'large'.
   * 
   * @return  QueryBuilder  This instance.
   */
  public function setProfileLarge() {
    return $this->setParameter('profile', 'large');
  }
  
  /**
   * Sets the value for the specified parameter.
   * 
   * @param   string  $name   The name of the parameter.
   * @param   scalar  $value  The value to set.
   * @return  QueryBuilder  This instance.
   */
  public function setParameter($name, $value) {
    $this->params[$name] = [$value];
    
    return $this;
  }
  
  /**
   * Adds a value to the specified parameter.
   * 
   * @param   string  $name   The name of the parameter.
   * @param   scalar  $value  The value to add.
   * @return  QueryBuilder  This instance.
   */
  public function addParameter($name, $value) {
    $this->params[$name][] = $value;
    
    return $this;
  }
  
  /**
   * Removes the parameter with the specified name.
   * 
   * If the parameter does not exist then it does nothing.
   * 
   * @param   string  $name   The name of the parameter to remove.
   * @return  QueryBuilder  This instance.
   */
  public function removeParameter($name) {
    if (array_key_exists($name, $this->params)) {
      unset($this->params[$name]);
    }
    
    return $this;
  }
  
  /**
   * Returns the string representation of the query.
   * 
   * @return  string
   */
  public function getQueryString() {
    $params = [];
    
    foreach ($this->params as $name => $values) {
      // Separates each values with a comma.
      $params[$name] = implode(',', $values);
    }
    
    return http_build_query($params);
  }
}