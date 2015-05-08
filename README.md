# Excel Stream Writer
-----

Klasa umożliwiająca strumieniowy zapis plików w formacie *.xls* (BIFF - Binary Interchange File Format).

Domyślnie przyjęta została obsługa formatu BIFF8 (MS Excel 97 / 2000, MS Excel 2002 itd.) z uwagi na obsługiwane kodowanie Unicode (UTF16-LE). 
W celu dostosowania do obslugi innego formatu, np. BIFF5 konieczna będzie zmiana znacznika poczatku pliku (@see *TSwiackiewicz\ExcelStreamWriter\Record\Bof\Bof*) oraz sposobu reprezentacji stringów (@see *TSwiackiewicz\ExcelStreamWriter\Record\Cell\StringCell::getValue()*).

Dokument w formacie BIFF składa się z rekordów o ustalonej strukturze - wszelkie dane (łącznie z BOF, EOF czy ustawianiem strony kodowej) do arkusza wstawiane są poprzez dodanie odpowiedniego rekordu do dokumentu. Pliki w formacie BIFF rozpoczynają się znacznikiem BOF (Beginning of File) oraz koncza znacznikiem EOF (End of File).

Klasa wspiera różne architektury (Little-Endian, Big-Endian), obsługiwany jest pojedynczy arkusz (worksheet) bez obsługi nagłówka continue (gdy długość rekordu przekracza 8228 b), rich text, obrazków itd.

Ponadto generowany arkusz został ograniczony do 65535 wierszy oraz 255 kolumn (ograniczenia formatu .xls)

Udostępnione zostały następujące metody publiczne:

 * addCell($row, $col, $value) - wstawia komórkę o podanej zawartości pod wskazany adres ($row, $col), $row - [0..65535], $col - [0..255]
 * addRow($row, array $data) - wstawia dane do wiersza o podanym numerze ($row)
 * addNextRow(array $data) - wstawia kolejny wiersz z danymi
 
 Przykład użycia:
 
 ```
$writer = new ExcelStreamWriter('/tmp/test.xls');
$writer->open();
$writer->addNextRow([
    'Lp',
    'Subject',
    'Content'
]);
$writer->addNextRow([
    1,
    'Title #1',
    'zażółć gęślą jaźń'
]);
$writer->addNextRow([
    2,
    'Title #2',
    'Lorem ipsum...'
]);
$writer->addNextRow([
    3,
    'Title #3',
    ''
]);
$writer->addNextRow([
    4,
    'Title #4',
    -99.99
]);
$writer->close();
 ```


