<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'a99953zd_photo' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'a99953zd_photo' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'mBQ95nd3' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'W;&.++[tHC&M[Sx#-ssf$tt^9wD) P3CHJ=6R~Y1>HYF~BLgm<knygy[Y#aNR/z$' );
define( 'SECURE_AUTH_KEY',  '*p@HMDXzL!ric[q(#HTadqc4n3j65{Jy&!B-lhPpAF4LWb;;$uY$k+7erKZ0*^W:' );
define( 'LOGGED_IN_KEY',    'iq|;YyVYSQrX,s2`p{V?I0k{R)-s)Z8kY1$i }UQNiq{D8t+]fTqS[D:C!]2)VX[' );
define( 'NONCE_KEY',        'wQ%3L&lYgfXVCKqW!j~,<o8Zd DX]3Sxv)|E@]Vj^63Cp4]sv.-rUktntvIo>*:J' );
define( 'AUTH_SALT',        '}f1vb7ju4Efoa0s~[:i[%`gJ=ZQ ;9yK;_GKO7KbQlzBI@s(K)s$WC8baBIN#W/Y' );
define( 'SECURE_AUTH_SALT', 'am@0:HX+g,]bPDD+?Cc|f]qo((gwiDS?P<}b4V$6Z$D$j`nG|*A3kcEq>XZ6IlqN' );
define( 'LOGGED_IN_SALT',   '[!Y`w:7LSOoW 0X-m.w(~Zv|$g]h=V]:<Z?1idi|xBT9<6+Pws$f]b+c31iJSn~v' );
define( 'NONCE_SALT',       't2132Q0(=RbN<ziAB(7jiYP;kS$;O_cv@KZoY$US3N+.g%WdDlg;OJ?e)BHznL.?' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );
