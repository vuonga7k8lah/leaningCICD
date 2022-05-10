<?php

namespace WilokeTimeline\Share;

class AutoPrefix
{
	public static function namePrefix($name)
	{
		return strpos($name, WILOKE_WILOKETIMELINE_PREFIX) === 0 ? $name : WILOKE_WILOKETIMELINE_PREFIX . $name;
	}

	public static function removePrefix(string $name): string
	{
		if (strpos($name, WILOKE_WILOKETIMELINE_PREFIX) === 0) {
			$name = str_replace(WILOKE_WILOKETIMELINE_PREFIX, '', $name);
		}

		return $name;
	}
}