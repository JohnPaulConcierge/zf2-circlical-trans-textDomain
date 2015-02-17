<?php

namespace CirclicalTwigTrans\Model\Twig;

use Zend\I18n\Translator\TranslatorInterface;

class TransParser extends \Twig_Extensions_TokenParser_Trans
{
    protected $translator;

    public function __construct( TranslatorInterface $translator )
    {
        $this->translator = $translator;
    }

    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token A Twig_Token instance
     *
     * @return Twig_NodeInterface A Twig_NodeInterface instance
     */
    public function parse(\Twig_Token $token)
    {
        $lineno     = $token->getLine();
        $stream     = $this->parser->getStream();
        $count      = null;
        $plural     = null;

        if (!$stream->test(\Twig_Token::BLOCK_END_TYPE))
        {
            $body = $this->parser->getExpressionParser()->parseExpression();
        }
        else
        {
            $stream->expect(\Twig_Token::BLOCK_END_TYPE);
            $body = $this->parser->subparse(array($this, 'decideForFork'));

            if ('plural' === $stream->next()->getValue()) {
                $count = $this->parser->getExpressionParser()->parseExpression();
                $stream->expect(\Twig_Token::BLOCK_END_TYPE);
                $plural = $this->parser->subparse(array($this, 'decideForEnd'), true);
            }
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $this->checkTransString($body, $lineno);

        $t = new TransNode( $body, $plural, $count, null,  $lineno, $this->getTag() );
        $t->setTranslator( $this->translator );
        return $t;
    }

}
