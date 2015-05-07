<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\PackFormatter;

use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;

/**
 * Pusty formatter metody pack()
 */
class NullPackFormatter extends PackFormatter
{

    /**
     * Pobranie formatu dla metody pack() dla rozpoznanego trybu endian
     * 
     * @param array $args lista poszczegolnych argumentow dla metody pack
     * @throws \InvalidArgumentException nieprawidlowy typ argumentu
     * @return string format dla metody pack() dostosowany dla rozpoznanego trybu endian
     */
    public function getFormat(array $args)
    {
        return '';
    }
}
