<?php
namespace Mac\Morphy;

use phpMorphy;
use phpMorphy_FilesBundle;

/**
 * @SuppressWarnings(StaticAccess)
 */
class Morphy implements MorphyInterface
{
    /**
     * @var phpMorphy
     */
    public $enMorphy;

    /**
     * @var phpMorphy
     */
    public $ruMorphy;

    /**
     * Available grammems
     *
     * http://phpmorphy.sourceforge.net/dokuwiki/manual-graminfo
     *
     * @var array
     */
    protected $grammems = array(
        // РОД
        'МР', // мужской род
        'ЖР', // женский род
        'СР', // средний род
        'МР-ЖР', // общий род (сирота, пьяница)

        // ЧИСЛО
        'ЕД', // единственное число
        'МН', // множественное число

        // ПАДЕЖ
        'ИМ', // именительный
        'РД', // родительный
        'ДТ', // дательный
        'ВН', // винительный
        'ТВ', // творительный
        'ПР', // предложный
        'ЗВ', // звательный (отче, боже)
        '2', // второй родительный или второй предложный падежи

        // ВРЕМЯ
        'НСТ', // настоящее время
        'БУД', // будущее время
        'ПРШ', // прошедшее время

        // ЛИЦО
        '1Л', // первое лицо
        '2Л', // второе лицо
        '3Л', // третье лицо

        // ОДУШЕВЛЕННОСТЬ
        'ОД', // одушевленное
        'НО', // неодушевленное

        // ВИД
        'СВ', // совершенный вид
        'НС', // несовершенный вид

        // ПЕРЕХОДНОСТЬ
        'НР', // переходный
        'ПЕ', // непереходный

        // ЗАЛОГ
        'ДСТ', // действительный залог
        'СТР', // страдательный залог

        // ДРУГОЕ
        '0', // неизменяемое
        'БЕЗД', // безличный глагол
        'ПВЛ', // повелительное наклонение (императив)
        'ПРИТЯЖ', // притяжательное (не используется)
        'ПРЕВ', // превосходная степень (для прилагательных)
        'СРАВН', // сравнительная степень (для прилагательных)
        'КАЧ', // качественное прилагательное
        'ДФСТ', // ??? прилагательное (не используется)

        // СЕМАНТИЧЕСКИЕ ПРИЗНАКИ
        'ИМЯ', // имя (Иван, Михаил)
        'ФАМ', // фамилия (Иванов, Сидоров)
        'ОТЧ', // отчество (Иванович, Михайлович)
        'ЛОК', // топоним (Москва, Лена, Эверест)
        'АББР', // аббревиатура (КПСС, РОНО)
        'ОРГ', // организация
        'ВОПР', // вопросительное наречие
        'УКАЗАТ', // указательное наречие
        'ЖАРГ', // жаргонизм
        'РАЗГ', // разговорный
        'АРХ', // архаизм
        'ОПЧ', // опечатка
        'ПОЭТ', // поэтическое
        'ПРОФ', // профессионализм
        'ПОЛОЖ' // ??? (не используется)
    );

    /**
     * Create PHP Morphy instance
     *
     * Available storage options:
     * PHPMORPHY_STORAGE_FILE - slowest but less memory
     * PHPMORPHY_STORAGE_MEM - fastest but more memory
     *
     * @param string $storage
     */
    public function __construct($storage = PHPMORPHY_STORAGE_FILE)
    {
        $this->ruMorphy = new phpMorphy(
            new phpMorphy_FilesBundle(dirname(__FILE__) . '/../lib/phpmorphy/ru/', 'rus'),
            array('storage' => $storage)
        );

        $this->enMorphy = new phpMorphy(
            new phpMorphy_FilesBundle(dirname(__FILE__) . '/../lib/phpmorphy/en/', 'eng'),
            array('storage' => $storage)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function base($word)
    {
        $sanitizedWord = $this->sanitize($word);
        $result = $this->getMorphy($sanitizedWord)->getBaseForm($sanitizedWord);
        return $result ? array_shift($this->unsanitize($word, $result)) : null;
    }

    /**
     * @param string $word to sanitize
     * @return string
     */
    protected function sanitize($word)
    {
        return mb_strtoupper(trim($word), 'UTF-8');
    }

    /**
     * @param string $word
     * @return phpMorphy
     */
    protected function getMorphy($word)
    {
        return $this->isRussian($word) ? $this->ruMorphy : $this->enMorphy;
    }

    /**
     * @param string $word to check
     * @return bool
     */
    protected function isRussian($word)
    {
        return preg_match('/[А-Я]/u', $word) ? true : false;
    }

    /**
     * @param string $originalWord
     * @param array $resultWords words to unsanitize
     * @return array of unstanitized words
     */
    protected function unsanitize($originalWord, array $resultWords = array())
    {
        $result = array();
        foreach ($resultWords as $word) {
            $item = mb_strtolower($word, 'UTF-8');
            if ($this->isAllLettersUpperCase($originalWord)) {
                $item = mb_strtoupper($word, 'UTF-8');
            } elseif ($this->isFirstLetterUpperCase($originalWord)) {
                $item = $this->ucfirst(mb_strtolower($word, 'UTF-8'));
            }
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @param string $word to check
     * @return bool
     */
    protected function isAllLettersUpperCase($word)
    {
        return preg_match('/^[A-ZА-Я]+$/u', $word) === 1;
    }

    /**
     * @param string $word to check
     * @return bool
     */
    protected function isFirstLetterUpperCase($word)
    {
        return preg_match('/^[A-ZА-Я]/u', $word) === 1;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function ucfirst($string)
    {
        $strlen = mb_strlen($string, 'UTF-8');
        $firstChar = mb_substr($string, 0, 1, 'UTF-8');
        $then = mb_substr($string, 1, $strlen - 1, 'UTF-8');
        return mb_strtoupper($firstChar, 'UTF-8') . $then;
    }

    /**
     * {@inheritdoc}
     */
    public function all($word)
    {
        $sanitizedWord = $this->sanitize($word);
        $result = $this->getMorphy($sanitizedWord)->getAllForms($sanitizedWord);
        return $result ? $this->unsanitize($word, $result) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function where($word)
    {
        return $this->cast($word, array('ЕД', 'ПР'));
    }

    public function cast($word, array $grammems)
    {
        $sanitizedWord = $this->sanitize($word);
        $sanitizedGrammems = $this->sanitizeGrammems($grammems);
        if (empty($sanitizedGrammems)) {
            return null;
        }
        $result = $this->getMorphy($sanitizedWord)->castFormByGramInfo($sanitizedWord, null, $sanitizedGrammems, true);
        return $result ? $this->unsanitize($word, $result) : null;
    }

    /**
     * Sanitizes given grammems to allowed ones
     *
     * @param array $grammems
     * @return array
     */
    protected function sanitizeGrammems(array $grammems)
    {
        $result = array();
        foreach ($grammems as $grammem) {
            $upper = mb_strtoupper(trim(strval($grammem)), 'UTF-8');
            if (in_array($upper, $this->grammems)) {
                $result[] = $upper;
            }
        }
        return $result;
    }
}
