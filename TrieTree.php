<?php

/**
 * UTF8 TrieTree实现
 * PHP8.1+
 * 
 * @author Sunch233
 */
class TrieTree {

	public static function splitUTF8Chars(string $str) : array{
		return mb_str_split($str, 1, 'UTF-8');
	}

	protected TrieTreeNode $root;
	public function __construct(array $appendWords = []) {
		$this->root = new TrieTreeNode(true);
		foreach ($appendWords as $word) {
			$this->append($word);
		}
	}

	/**
	 * 添加关键词
	 */
	public function append(string $str) : void{
		$chars = self::splitUTF8Chars($str);
		$current = $this->root;

		foreach($chars as $char){
			if(!isset($current->children[$char])){
				$current->children[$char] = new TrieTreeNode();
			}
			$current = $current->children[$char];
		}
		$current->isEnd = true;
	}

	/**
	 * 查找文本中是否有字符串
	 */
	public function search(string $str) : bool{
		$chars = self::splitUTF8Chars($str);
		$current = $this->root;

		foreach($chars as $char){
			if(isset($current->children[$char])){ //命中索引
				$node = $current->children[$char];
				if($node->isEnd){ //找到字符串
					return true;
				}
				$current = $current->children[$char];
			}elseif(!$current->isRoot){
				$current = $this->root;
				if(isset($current->children[$char])){
					$node = $current->children[$char];
					if($node->isEnd){ //找到字符串
						return true;
					}
				}
			}
		}
		return false;
	}
}

class TrieTreeNode {
	public function __construct(public bool $isRoot = false) {
		//NOOP
	}
	/** @var TrieTreeNode[]  */
	public array $children = [];
	public bool $isEnd = false;
}