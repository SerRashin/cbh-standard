<?php

namespace CBH\CBHStandard;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Проверяет наличие декларирования declare(strict_type = 1)
 */
class DeclareStrictSniff implements Sniff
{
    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
     */
    public function register(): array
    {
        return [T_OPEN_TAG];
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @param File $file
     * @param int  $stackPtr
     */
    public function process(File $file, $stackPtr): void
    {
        $fileBeginning = $file->getTokensAsString(0, 12);
        $declare       = strpos($fileBeginning, 'declare(strict_type = 1)');

        if (!$declare) {
            $error = 'No declare(strict_type = 1) expression found at the beginning of the file';
            $file->addError($error, $stackPtr, 'NotFound');
        }
    }
}
