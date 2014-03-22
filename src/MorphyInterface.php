<?php
namespace Morphy;

interface MorphyInterface
{
    /**
     * Returns base form of given word or null if nothing found
     *
     * @param string $word
     * @return string|null
     */
    public function base($word);

    /**
     * Return all forms of given word or null if nothing found
     *
     * @param string $word
     * @return array|null
     */
    public function all($word);

    /**
     * Cast given word to specific form, null will be returned if word can not be casted
     *
     * @param string $word to cast
     * @param array $grammems desired form
     * @return array|null
     */
    public function cast($word, array $grammems);

    /**
     * Cast given word to where form, null will be returned if word can not be casted
     *
     * @param string $word to cast
     * @return array|null
     */
    public function where($word);
}
