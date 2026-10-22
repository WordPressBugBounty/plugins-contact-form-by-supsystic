<?php
/**
 * Sandbox policy for forms templates rendering.
 * Default Twig_Sandbox_SecurityPolicy allows to call any method of template object (_self),
 * that opens whole Twig environment, so here access to objects is closed completely:
 * forms templates work only with arrays and few allowed tags, filters and functions.
 */
class twigSecurityPolicyCfs extends Twig_Sandbox_SecurityPolicy
{
  public function __construct()
  {
    parent::__construct(
      ['if', 'for', 'set', 'spaceless', 'verbatim', 'raw'],
      ['escape', 'e', 'raw', 'upper', 'lower', 'capitalize', 'title', 'trim', 'nl2br', 'striptags', 'default', 'length', 'round', 'number_format', 'abs', 'join', 'first', 'last', 'date'],
      [],
      [],
      ['adjust_brightness', 'adjust_opacity', 'hex_to_rgba_str', 'min', 'max'],
    );
  }
  public function checkMethodAllowed($obj, $method)
  {
    throw new Twig_Sandbox_SecurityError(sprintf('Calling "%s" method on a "%s" object is not allowed.', $method, get_class($obj)));
  }
  public function checkPropertyAllowed($obj, $property)
  {
    throw new Twig_Sandbox_SecurityError(sprintf('Calling "%s" property on a "%s" object is not allowed.', $property, get_class($obj)));
  }
}
