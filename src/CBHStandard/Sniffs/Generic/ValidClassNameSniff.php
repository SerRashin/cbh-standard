<?php

namespace CBH\CBHStandard;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Обязывает указывать валидные имена с суфиксами
 *
 * @package CBH\CBHStandard
 */
class ValidClassNameSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
        'PHP',
    );
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_INTERFACE,
            T_TRAIT,
            T_EXTENDS,
            T_ABSTRACT
        );
    }
    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens   = $phpcsFile->getTokens();
        $line     = $tokens[$stackPtr]['line'];
        while ($tokens[$stackPtr]['line'] == $line) {
            /*
             * Suffix interfaces with Interface;
             */
            if ('T_INTERFACE' == $tokens[$stackPtr]['type']) {
                $name = $phpcsFile->findNext(T_STRING, $stackPtr);
                if ($name && substr($tokens[$name]['content'], -9) != 'Interface') {
                    $phpcsFile->addError(
                        'Interface name is not suffixed with "Interface"',
                        $stackPtr,
                        'InvalidInterfaceName'
                    );
                }
                break;
            }
            /*
             * Suffix traits with Trait;
             */
            if ('T_TRAIT' == $tokens[$stackPtr]['type']) {
                $name = $phpcsFile->findNext(T_STRING, $stackPtr);
                if ($name && substr($tokens[$name]['content'], -5) != 'Trait') {
                    $phpcsFile->addError(
                        'Trait name is not suffixed with "Trait"',
                        $stackPtr,
                        'InvalidTraitName'
                    );
                }
                break;
            }
            /*
             * Suffix exceptions with Exception;
             */
            if ('T_EXTENDS' == $tokens[$stackPtr]['type']) {
                $extend = $phpcsFile->findNext(T_STRING, $stackPtr);
                if ($extend
                    && substr($tokens[$extend]['content'], -9) == 'Exception'
                ) {
                    $class = $phpcsFile->findPrevious(T_CLASS, $stackPtr);
                    $name = $phpcsFile->findNext(T_STRING, $class);
                    if ($name
                        && substr($tokens[$name]['content'], -9) != 'Exception'
                    ) {
                        $phpcsFile->addError(
                            'Exception name is not suffixed with "Exception"',
                            $stackPtr,
                            'InvalidExceptionName'
                        );
                    }
                }
                break;
            }
            $stackPtr++;
        }
        return;
    }
}
