<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckBalancedBrackets extends Command
{
    protected $signature = 'check:balanced-brackets';

    protected $description = 'Check if the given string has balanced brackets';

    public function handle(): void
    {
        $expression = $this->ask('Enter the expression to check for balanced brackets:');

        $result = $this->isBalanced($expression);

        if ($result) {
            $this->info('The expression has balanced brackets.');
        } else {
            $this->warn('The expression does not have balanced brackets.');
        }

    }

    private function isBalanced($string): bool
    {
        $string = $this->removeNonBrackets($string);

        $tokenPairs = ['{}', '[]', '()'];

        do {
            $prevString = $string;
            $string     = str_replace($tokenPairs, '', $string);
        } while ($prevString !== $string);

        return empty($string);
    }

    private function removeNonBrackets($string): string
    {
        return preg_replace("/[^{}\[\]\(\)]/", '', $string);
    }
}
