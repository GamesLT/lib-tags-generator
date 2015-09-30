<?php

namespace MekDrop\TagsGenerator;

/**
 * Simple class to generate automatically tags for text content
 *
 * @author     Raimondas Rimkevičius (aka MekDrop)
 * @license    MIT License
 */
class TagsGenerator {

    /**
     * Here is array with important words (these words has priority)
     *
     * @var array 
     */
    public $importantWords = array();
    
    /**
     * Gets instance of Tags Generator
     * 
     * @staticvar null|TagsGenerator $instance
     * 
     * @return TagsGenerator
     */
    public static function getInstance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * Constructor
     * 
     * @param array $importantWords     Array with important words
     */
    public function __construct($importantWords = array()) {
        $this->importantWords = $importantWords;
    }

    /**
     * Generates tags list
     *
     * @param string $title 	Title of content
     * @param string $shortInfo	Short description of the content
     * @param string $content	Content
     * @param int    $limit     How much tags should be returned
     *
     * @return string
     */
    public function findTags($title, $shortInfo, $content, $limit = 5) {
        $tmpTags = array(
            $this->findImportantWords($title),
            $this->findTagsAndCalc($title),
            $this->findImportantWords($shortInfo),
            $this->findTagsAndCalc($shortInfo),
            $this->findImportantWords($content),
            $this->findTagsAndCalc($content)
        );
        $tags = array();
        $count = count($tmpTags);
        for ($i = 0; $i < $count; $i++) {
            foreach ($tmpTags[$i] as $tag => $value) {
                if (!isset($tags[$tag])) {
                    $tags[$tag] = 0;
                }
                $tags[$tag] += ($value + 1) * ($count - $i);
            }
        }
        foreach ($this->findTagsAbbreviations($tags) as $stag => $tag) {
            $tags[$tag] += $tags[$stag];
            unset($tags[$stag]);
        }
        foreach ($this->findTagsCompanies($tags) as $tag => $info) {
            $tags[$tag] += $info['value'];
            foreach ($info['items'] as $oldName => $newName) {
                $tags[$newName] = $tags[$oldName];
                unset($tags[$oldName]);
            }
        }
        foreach (array_keys($tags) as $tag) {
            if (strpos($tag, '$') > 0 || is_numeric($tag) || substr($tag, -1) == 'w') {
                unset($tags[$tag]);
            }
        }
        arsort($tags);
        $tags = array_slice($tags, 0, $limit);
        return mb_str_replace(array(',,', ', ,', ',:'), ',', implode(',', array_keys($tags)));
    }

    /**
     * Generates short name
     *
     * @param string $name  Name to 
     * 
     * @return array
     */
    public function generateShortName($name) {
        $parts = explode(' ', $name);
        $nname = '';
        if (count($parts) < 2) {
            return $name;
        }
        foreach ($parts as $part) {
            $nname .= $part{0};
        }
        return $nname;
    }

    /**
     * Finds tags and calculates values
     *
     * @param string $content	Content for what we should generate tags
     *
     * @return array
     */
    private function findTagsAndCalc($content) {
        $tags = array();
        if (mb_strpos($content, ' ') < 1) {
            return $tags;
        }
        $content = trim(mb_str_replace(array("\r", "\t"), " ", mb_str_replace(array('„', '“'), '"', strip_tags($content))));
        preg_match_all('/"(.+)"|\[i\](.*)\[\/i\]|\[u\](.*)\[\/u\]|\[b\](.*)\[\/b\]|\[u\](.*)\[\/u\]| (\b[a-zA-Z0-9][A-Z0-9]+\b)/U', $content, $matches);
        $count = count($matches) - 1;
        $matches = array_slice($matches, 1, $count);
        for ($i = 0; $i < $count; $i++) {
            foreach ($matches[$i] as $match) {
                if ($match == '') {
                    continue;
                }
                $parts = explode(',', $match);
                $match = strtolower(trim($parts[0]));
                if ($match == '' || is_numeric($match)) {
                    continue;
                }
                $tmpTags = $this->findTagsAndCalc($match);
                if (!empty($tmpTags)) {
                    foreach ($tmpTags as $tag => $value) {
                        if (!isset($tags[$tag])) {
                            $tags[$tag] = 0;
                        }
                        $tags[$tag] += ($value + 1) * 2;
                    }
                } else {
                    if (!isset($tags[$match])) {
                        $tags[$match] = 0;
                    } else {
                        $tags[$match]++;
                    }
                }
            }
        }
        return $tags;
    }

    /**
     * Finds tags by short names
     *
     * @param array $tags   List with tags and their values
     *
     * @return array
     */
    protected function findTagsAbbreviations(&$tags) {
        $ret = array();
        foreach ($tags as $tag => $value) {
            $stag = $this->generateShortName($tag);
            if (($tag != $stag) && (isset($tags[$stag]))) {
                $ret[$stag] = $tag;
            } else {
                foreach ($tags as $tag2 => $value) {
                    if ($tag2 == $tag) {
                        continue;
                    }
                    $stag = mb_substr($tag2, -mb_strlen($tag));
                    if ($stag == $tag) {
                        $ret[$tag] = $tag2;
                        break;
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Find company tag names
     *
     * @param array $tags   List with tags and their values
     *
     * @return array
     */
    public function findTagsCompanies(&$tags) {
        $ret = array();
        foreach (array_keys($tags) as $tag) {
            foreach ($tags as $tag2 => $value2) {
                if ($tag2 == $tag) {
                    continue;
                }
                $stag = mb_substr($tag2, 0, mb_strlen($tag));
                if ($stag == $tag) {
                    if (!isset($ret[$tag])) {
                        $ret[$tag] = array('value' => 0, 'items' => array());
                    }
                    $ret[$tag]['value'] += $value2;
                    $ret[$tag]['items'][$tag2] = trim(mb_substr($tag2, mb_strlen($tag)));
                }
            }
        }
        return $ret;
    }

    /**
     * Finds most important words in content
     *
     * @param string $content 	Content where to search words
     *
     * @return array
     */
    public function findImportantWords($content) {
        $tags = array();
        foreach ($this->importantWords as $word) {
            $count = count(explode($word, $content)) - 1;
            if ($count > 0) {
                $tags[$word] = ($count - 1) * 3;
            }
        }
        return $tags;
    }

}
