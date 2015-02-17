<?php
namespace CirclicalTwigTrans\Model\Twig;

use \Zend\I18n\View\Helper\Translate;

class TransNode extends \Twig_Extensions_Node_Trans
{

    /**
     * @var \Zend\I18n\View\Helper\Translate
     */
    private $translator;

    /**
     * @param Translate $translate
     *
     * @return $this
     */
    public function setTranslator(Translate $translate)
    {
        $this->translator = $translate;
        return $this;
    }

    /**
     * @return \Zend\I18n\View\Helper\Translate
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param \Twig_Compiler $compiler
     */
    public function fcompile(\Twig_Compiler $compiler){
        die( "HERE" );
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        list($msg, $vars) = $this->compileString($this->getNode('body'));

        if (null !== $this->getNode('plural')) {
            list($msgp, $vars1) = $this->compileString($this->getNode('plural'));
            $vars = array_merge($vars, $vars1);
        }

        $is_plural  = null === $this->getNode('plural') ? false : true;
        $function   = null === $this->getNode('plural') ? 'gettext' : 'ngettext';

        if ($vars) {

            $compiler->raw('echo strtr(' . $function . '(' );

            if( $is_plural )
            {
                $t  = $this->getTranslator()->getTranslator()->translate(
                    $msg->nodes[0]->getAttribute('value'),
                    $this->getTranslator()->getTranslatorTextDomain(),
                    $this->getTranslator()->getTranslator()->getLocale()
                );

                if( is_array( $t ) )
                {
                    $s = $t[0];
                    $p = $t[1];
                }
                else
                {
                    $s = $t;
                    $p = $msgp->nodes[0]->getAttribute('value');
                }

                $compiler
                    ->repr( $s )
                    ->raw(',' )
                    ->repr( $p )
                    ->raw(', abs(')
                    ->subcompile($this->getNode('count'))
                    ->raw(')');
            }
            else
            {
                $compiler->repr($this->getTranslator()->getTranslator()->translate(
                    $msg->nodes[0]->getAttribute('value'),
                    $this->getTranslator()->getTranslatorTextDomain(),
                    $this->getTranslator()->getTranslator()->getLocale()
                ));
            }
            $compiler->raw('), array(');

            foreach ($vars as $var) {
                if ('count' === $var->getAttribute('name')) {
                    $compiler
                        ->string('%count%')
                        ->raw(' => abs(')
                        ->subcompile($this->getNode('count'))
                        ->raw(') ')
                    ;
                } else {
                    $compiler
                        ->string('%'.$var->getAttribute('name').'%')
                        ->raw(' => ')
                        ->subcompile($var)
                        ->raw(', ')
                    ;
                }
            }

            $compiler->raw("));\n");

        } else {

            $srcnode = $is_plural ? $msgp : $msg;
            $compiler->write('echo ');
            $compiler->repr( $this->getTranslator()->getTranslator()->translate(
                $srcnode->getAttribute('value'),
                $this->getTranslator()->getTranslatorTextDomain(),
                $this->getTranslator()->getTranslator()->getLocale()
            ) );
            $compiler->write(';' );

        }
    }

}
