<?php
namespace TSwiackiewicz\ExcelStreamWriter\PackFormatter;

use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

/**
 * Klasa do skladania formatu dla metody pack() w zaleznosci
 * od rozpoznanego trybu endian oraz podanych typow danych
 */
class PackFormatter
{

    /**
     * Predefiniowana stala dla pakowania do formatu short
     * 
     * @var string
     */
    const SHORT = 'short';

    /**
     * Predefiniowana stala dla pakowania do formatu long
     * 
     * @var string
     */
    const LONG = 'long';

    /**
     * Predefiniowana stala dla pakowania do formatu double
     * 
     * @var string
     */
    const DOUBLE = 'double';

    /**
     * Predefiniowana stala dla pakowania do formatu char
     * 
     * @var string
     */
    const CHAR = 'char';

    /**
     * Rozpoznany tryb endian: {ByteOrder::MACHINE_BYTE_ORDER, ByteOrder::LITTLE_ENDIAN, ByteOrder::BIG_ENDIAN}
     * 
     * @var string
     */
    private $endian;

    /**
     * Mapowanie poszczegolnych formatow na odpowiadajacy im format
     * w zaleznosci od rozpoznanego trybu endian
     * 
     * @var array
     */
    private $formatMap = [
        ByteOrder::MACHINE_BYTE_ORDER => [
            self::SHORT => 'S',
            self::LONG => 'L',
            self::DOUBLE => 'd',
            self::CHAR => 'C'
        ],
        ByteOrder::LITTLE_ENDIAN => [
            self::SHORT => 'v',
            self::LONG => 'V',
            self::DOUBLE => 'd',
            self::CHAR => 'C'
        ],
        ByteOrder::BIG_ENDIAN => [
            self::SHORT => 'n',
            self::LONG => 'N',
            self::DOUBLE => 'd',
            self::CHAR => 'C'
        ]
    ];

    /**
     * Inicjalizacja - rozpoznanie trybu endian
     * 
     * @param ByteOrder $byteOrder instancja klasy do rozpoznawania trybu endian
     */
    public function __construct(ByteOrder $byteOrder)
    {
        $this->endian = $byteOrder->getEndian();
    }

    /**
     * Pobranie rozpoznanego trybu endain
     * 
     * @return string tryb endian
     */
    public function getEndian()
    {
        return $this->endian;
    }

    /**
     * Pobranie formatu dla metody pack() dla rozpoznanego trybu endian
     * 
     * @param array $args lista poszczegolnych argumentow dla metody pack
     * @throws \InvalidArgumentException nieprawidlowy typ argumentu
     * @return string format dla metody pack() dostosowany dla rozpoznanego trybu endian
     */
    public function getFormat(array $args)
    {
        $format = '';
        
        foreach ($args as $arg) {
            if (! isset($this->formatMap[$this->endian][$arg])) {
                throw new \InvalidArgumentException('Unsupported format arg!');
            }
            
            $format .= $this->formatMap[$this->endian][$arg];
        }
        
        return $format;
    }
}
