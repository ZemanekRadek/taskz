<?php
spl_autoload_register(
	function ($class_name) {
		static $class_map = array(
			'l10n\\Language\\AfrikaansLanguage'        => 'Language/AfrikaansLanguage.php',
			'l10n\\Language\\AkanLanguage'             => 'Language/AkanLanguage.php',
			'l10n\\Language\\AlbanianLanguage'         => 'Language/AlbanianLanguage.php',
			'l10n\\Language\\AmharicLanguage'          => 'Language/AmharicLanguage.php',
			'l10n\\Language\\ArabicLanguage'           => 'Language/ArabicLanguage.php',
			'l10n\\Language\\AragoneseLanguage'        => 'Language/AragoneseLanguage.php',
			'l10n\\Language\\ArmenianLanguage'         => 'Language/ArmenianLanguage.php',
			'l10n\\Language\\AssameseLanguage'         => 'Language/AssameseLanguage.php',
			'l10n\\Language\\AymaraLanguage'           => 'Language/AymaraLanguage.php',
			'l10n\\Language\\AzerbaijaniLanguage'      => 'Language/AzerbaijaniLanguage.php',
			'l10n\\Language\\BasqueLanguage'           => 'Language/BasqueLanguage.php',
			'l10n\\Language\\BelarusianLanguage'       => 'Language/BelarusianLanguage.php',
			'l10n\\Language\\BengaliLanguage'          => 'Language/BengaliLanguage.php',
			'l10n\\Language\\BosnianLanguage'          => 'Language/BosnianLanguage.php',
			'l10n\\Language\\BretonLanguage'           => 'Language/BretonLanguage.php',
			'l10n\\Language\\BulgarianLanguage'        => 'Language/BulgarianLanguage.php',
			'l10n\\Language\\BurmeseLanguage'          => 'Language/BurmeseLanguage.php',
			'l10n\\Language\\CatalanLanguage'          => 'Language/CatalanLanguage.php',
			'l10n\\Language\\ChineseLanguage'          => 'Language/ChineseLanguage.php',
			'l10n\\Language\\CroatianLanguage'         => 'Language/CroatianLanguage.php',
			'l10n\\Language\\CzechLanguage'            => 'Language/CzechLanguage.php',
			'l10n\\Language\\DanishLanguage'           => 'Language/DanishLanguage.php',
			'l10n\\Language\\DutchLanguage'            => 'Language/DutchLanguage.php',
			'l10n\\Language\\DzongkhaLanguage'         => 'Language/DzongkhaLanguage.php',
			'l10n\\Language\\EnglishLanguage'          => 'Language/EnglishLanguage.php',
			'l10n\\Language\\EsperantoLanguage'        => 'Language/EsperantoLanguage.php',
			'l10n\\Language\\EstonianLanguage'         => 'Language/EstonianLanguage.php',
			'l10n\\Language\\FaroeseLanguage'          => 'Language/FaroeseLanguage.php',
			'l10n\\Language\\FinnishLanguage'          => 'Language/FinnishLanguage.php',
			'l10n\\Language\\FrenchLanguage'           => 'Language/FrenchLanguage.php',
			'l10n\\Language\\FulahLanguage'            => 'Language/FulahLanguage.php',
			'l10n\\Language\\GaelicLanguage'           => 'Language/GaelicLanguage.php',
			'l10n\\Language\\GalicianLanguage'         => 'Language/GalicianLanguage.php',
			'l10n\\Language\\GeorgianLanguage'         => 'Language/GeorgianLanguage.php',
			'l10n\\Language\\GermanLanguage'           => 'Language/GermanLanguage.php',
			'l10n\\Language\\GreekLanguage'            => 'Language/GreekLanguage.php',
			'l10n\\Language\\GujaratiLanguage'         => 'Language/GujaratiLanguage.php',
			'l10n\\Language\\HausaLanguage'            => 'Language/HausaLanguage.php',
			'l10n\\Language\\HebrewLanguage'           => 'Language/HebrewLanguage.php',
			'l10n\\Language\\HindiLanguage'            => 'Language/HindiLanguage.php',
			'l10n\\Language\\HungarianLanguage'        => 'Language/HungarianLanguage.php',
			'l10n\\Language\\IcelandicLanguage'        => 'Language/IcelandicLanguage.php',
			'l10n\\Language\\ILanguage'                => 'Language/ILanguage.php',
			'l10n\\Language\\IndonesianLanguage'       => 'Language/IndonesianLanguage.php',
			'l10n\\Language\\InterlinguaLanguage'      => 'Language/InterlinguaLanguage.php',
			'l10n\\Language\\IrishLanguage'            => 'Language/IrishLanguage.php',
			'l10n\\Language\\ItalianLanguage'          => 'Language/ItalianLanguage.php',
			'l10n\\Language\\JapaneseLanguage'         => 'Language/JapaneseLanguage.php',
			'l10n\\Language\\KalaallisutLanguage'      => 'Language/KalaallisutLanguage.php',
			'l10n\\Language\\KannadaLanguage'          => 'Language/KannadaLanguage.php',
			'l10n\\Language\\KazakhLanguage'           => 'Language/KazakhLanguage.php',
			'l10n\\Language\\KhmerLanguage'            => 'Language/KhmerLanguage.php',
			'l10n\\Language\\KinyarwandaLanguage'      => 'Language/KinyarwandaLanguage.php',
			'l10n\\Language\\KirghizLanguage'          => 'Language/KirghizLanguage.php',
			'l10n\\Language\\KoreanLanguage'           => 'Language/KoreanLanguage.php',
			'l10n\\Language\\KurdishLanguage'          => 'Language/KurdishLanguage.php',
			'l10n\\Language\\LaoLanguage'              => 'Language/LaoLanguage.php',
			'l10n\\Language\\LatvianLanguage'          => 'Language/LatvianLanguage.php',
			'l10n\\Language\\LingalaLanguage'          => 'Language/LingalaLanguage.php',
			'l10n\\Language\\LithuanianLanguage'       => 'Language/LithuanianLanguage.php',
			'l10n\\Language\\LuxembourgishLanguage'    => 'Language/LuxembourgishLanguage.php',
			'l10n\\Language\\MacedonianLanguage'       => 'Language/MacedonianLanguage.php',
			'l10n\\Language\\MalagasyLanguage'         => 'Language/MalagasyLanguage.php',
			'l10n\\Language\\MalayalamLanguage'        => 'Language/MalayalamLanguage.php',
			'l10n\\Language\\MalayLanguage'            => 'Language/MalayLanguage.php',
			'l10n\\Language\\MalteseLanguage'          => 'Language/MalteseLanguage.php',
			'l10n\\Language\\MaoriLanguage'            => 'Language/MaoriLanguage.php',
			'l10n\\Language\\MarathiLanguage'          => 'Language/MarathiLanguage.php',
			'l10n\\Language\\MongolianLanguage'        => 'Language/MongolianLanguage.php',
			'l10n\\Language\\NepaliLanguage'           => 'Language/NepaliLanguage.php',
			'l10n\\Language\\NorthernSamiLanguage'     => 'Language/NorthernSamiLanguage.php',
			'l10n\\Language\\NorwegianBokmalLanguage'  => 'Language/NorwegianBokmalLanguage.php',
			'l10n\\Language\\NorwegianLanguage'        => 'Language/NorwegianLanguage.php',
			'l10n\\Language\\NorwegianNynorskLanguage' => 'Language/NorwegianNynorskLanguage.php',
			'l10n\\Language\\OccitanLanguage'          => 'Language/OccitanLanguage.php',
			'l10n\\Language\\OriyaLanguage'            => 'Language/OriyaLanguage.php',
			'l10n\\Language\\PanjabiLanguage'          => 'Language/PanjabiLanguage.php',
			'l10n\\Language\\PashtoLanguage'           => 'Language/PashtoLanguage.php',
			'l10n\\Language\\PersianLanguage'          => 'Language/PersianLanguage.php',
			'l10n\\Language\\PolishLanguage'           => 'Language/PolishLanguage.php',
			'l10n\\Language\\PortugueseLanguage'       => 'Language/PortugueseLanguage.php',
			'l10n\\Language\\RaetoRomanceLanguage'     => 'Language/RaetoRomanceLanguage.php',
			'l10n\\Language\\RomanianLanguage'         => 'Language/RomanianLanguage.php',
			'l10n\\Language\\RussianLanguage'          => 'Language/RussianLanguage.php',
			'l10n\\Language\\SerbianLanguage'          => 'Language/SerbianLanguage.php',
			'l10n\\Language\\SindhiLanguage'           => 'Language/SindhiLanguage.php',
			'l10n\\Language\\SinhalaLanguage'          => 'Language/SinhalaLanguage.php',
			'l10n\\Language\\SlovakLanguage'           => 'Language/SlovakLanguage.php',
			'l10n\\Language\\SlovenianLanguage'        => 'Language/SlovenianLanguage.php',
			'l10n\\Language\\SomaliLanguage'           => 'Language/SomaliLanguage.php',
			'l10n\\Language\\SouthernSothoLanguage'    => 'Language/SouthernSothoLanguage.php',
			'l10n\\Language\\SpanishLanguage'          => 'Language/SpanishLanguage.php',
			'l10n\\Language\\SundaneseLanguage'        => 'Language/SundaneseLanguage.php',
			'l10n\\Language\\SwahiliLanguage'          => 'Language/SwahiliLanguage.php',
			'l10n\\Language\\SwedishLanguage'          => 'Language/SwedishLanguage.php',
			'l10n\\Language\\TajikLanguage'            => 'Language/TajikLanguage.php',
			'l10n\\Language\\TamilLanguage'            => 'Language/TamilLanguage.php',
			'l10n\\Language\\TatarLanguage'            => 'Language/TatarLanguage.php',
			'l10n\\Language\\TeluguLanguage'           => 'Language/TeluguLanguage.php',
			'l10n\\Language\\ThaiLanguage'             => 'Language/ThaiLanguage.php',
			'l10n\\Language\\TibetanLanguage'          => 'Language/TibetanLanguage.php',
			'l10n\\Language\\TigrinyaLanguage'         => 'Language/TigrinyaLanguage.php',
			'l10n\\Language\\TurkishLanguage'          => 'Language/TurkishLanguage.php',
			'l10n\\Language\\TurkmenLanguage'          => 'Language/TurkmenLanguage.php',
			'l10n\\Language\\UighurLanguage'           => 'Language/UighurLanguage.php',
			'l10n\\Language\\UkrainianLanguage'        => 'Language/UkrainianLanguage.php',
			'l10n\\Language\\UrduLanguage'             => 'Language/UrduLanguage.php',
			'l10n\\Language\\UzbekLanguage'            => 'Language/UzbekLanguage.php',
			'l10n\\Language\\VietnameseLanguage'       => 'Language/VietnameseLanguage.php',
			'l10n\\Language\\WalloonLanguage'          => 'Language/WalloonLanguage.php',
			'l10n\\Language\\WesternFrisianLanguage'   => 'Language/WesternFrisianLanguage.php',
			'l10n\\Language\\WolofLanguage'            => 'Language/WolofLanguage.php',
			'l10n\\Language\\YorubaLanguage'           => 'Language/YorubaLanguage.php',

			'l10n\\Plural\\IPlural'                    => 'Plural/IPlural.php',
			'l10n\\Plural\\PluralRule0'                => 'Plural/PluralRule0.php',
			'l10n\\Plural\\PluralRule1'                => 'Plural/PluralRule1.php',
			'l10n\\Plural\\PluralRule10'               => 'Plural/PluralRule10.php',
			'l10n\\Plural\\PluralRule11'               => 'Plural/PluralRule11.php',
			'l10n\\Plural\\PluralRule12'               => 'Plural/PluralRule12.php',
			'l10n\\Plural\\PluralRule13'               => 'Plural/PluralRule13.php',
			'l10n\\Plural\\PluralRule14'               => 'Plural/PluralRule14.php',
			'l10n\\Plural\\PluralRule15'               => 'Plural/PluralRule15.php',
			'l10n\\Plural\\PluralRule16'               => 'Plural/PluralRule16.php',
			'l10n\\Plural\\PluralRule2'                => 'Plural/PluralRule2.php',
			'l10n\\Plural\\PluralRule3'                => 'Plural/PluralRule3.php',
			'l10n\\Plural\\PluralRule4'                => 'Plural/PluralRule4.php',
			'l10n\\Plural\\PluralRule5'                => 'Plural/PluralRule5.php',
			'l10n\\Plural\\PluralRule6'                => 'Plural/PluralRule6.php',
			'l10n\\Plural\\PluralRule7'                => 'Plural/PluralRule7.php',
			'l10n\\Plural\\PluralRule8'                => 'Plural/PluralRule8.php',
			'l10n\\Plural\\PluralRule9'                => 'Plural/PluralRule9.php',

			'l10n\\Translator\\IStorage'               => 'Translator/IStorage.php',
			'l10n\\Translator\\Translator'             => 'Translator/Translator.php'
		);

		if (isset($class_map[$class_name])) {
			require __DIR__ . '/l10n/' . $class_map[$class_name];
		}
	}
);
