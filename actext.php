<?php
/**
 * User: Shanika
 * Date: 6/23/2015
 * Time: 2:10 PM
 */

namespace AutoCompleteText;


class ac_text
{
    private $haystack;

    function __construct($h)
    {
        $this->haystack = $h;
    }

    public function search($needle, $limit = 5)
    {
        $needle = strtolower($needle);
        $result = array_values(preg_grep('/^' . $needle . '/i', $this->haystack));
        if (count($result) < $limit) {
            $result = array_merge($result, array_values(preg_grep('/ ' . $needle . '/i', $this->haystack)));
        }
        if (count($result) < $limit) {
            $similar = array();
            foreach ($this->haystack as $h) {
                $h_split = explode(' ', $h);
                if (count($h_split) > 1) {
                    foreach ($h_split as $hs) {
                        similar_text(strtolower($hs), $needle, $p);
                        $similar[$h] = max($p, $similar[$h]);
                    }
                } else {
                    similar_text(strtolower($h), $needle, $p);
                    $similar[$h] = $p;
                }
            }
            arsort($similar);
            $similar = array_keys($similar);
            while (count($result) < $limit) {
                $e = array_shift($similar);
                if (!in_array($e, $result)) {
                    $result[] = $e;
                }
            }
        }
        return $result;
    }
}
