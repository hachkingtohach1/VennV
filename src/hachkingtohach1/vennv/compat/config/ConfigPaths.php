<?php

namespace hachkingtohach1\vennv\compat\config;

abstract class ConfigPaths{

      public const POCKETMINE_API = 'pocketmine_api';
      public const NAME_OR_PREFIX = 'name';

      public const COMMANDS_NAME_COMMAND = 'commands.name_command';
      public const COMMANDS_PERMISSION = 'commands.permission';
      public const COMMANDS_SUBCOMMANDS_RELOAD_COMMAND = 'commands.subcommands.reload.command';
      public const COMMANDS_SUBCOMMANDS_RELOAD_PERMISSION = 'commands.subcommands.reload.permission';
      public const COMMANDS_SUBCOMMANDS_RELOAD_DESCRIPTION = 'commands.subcommands.reload.description';
      public const COMMANDS_SUBCOMMANDS_MENU_COMMAND = 'commands.subcommands.menu.command';
      public const COMMANDS_SUBCOMMANDS_MENU_PERMISSION = 'commands.subcommands.menu.permission';
      public const COMMANDS_SUBCOMMANDS_MENU_DESCRIPTION = 'commands.subcommands.menu.description';

      public const ALERTS_ENABLE = 'alerts.enable';
      public const ALERTS_ENABLE_PERMISSION = 'alerts.enable_permission';
      public const ALERTS_PERMISSION_ALERT = 'alerts.permision_alert';
      public const ALERTS_MESSAGE = 'alerts.message';
      public const ALERTS_LOGS_ENABLE = 'alerts.logs.enable';
      public const ALERTS_LOGS_RECENT = 'alerts.logs.recent';

      public const KICK_MESSAGE = 'kick.message';
      public const KICK_BROADCAST = 'kick.broadcast';
      public const KICK_COMMANDS = 'kick.commands';

      public const BAN_MESSAGE = 'ban.message';
      public const BAN_BROADCAST = 'ban.broadcast';
      public const BAN_COMMANDS = 'ban.commands';

      public const WEBHOOK_DISCORD_NAME_BOT = 'webhook.discord.name_bot';
      public const WEBHOOK_DISCORD_AVATAR_URL = 'webhook.discord.avatar_url';
      public const WEBHOOK_DISCORD_LOGS_ENABLE = 'webhook.discord.logs.enable';
      public const WEBHOOK_DISCORD_LOGS_URL = 'webhook.discord.logs.url';
      public const WEBHOOK_DISCORD_BAN_ENABLE = 'webhook.discord.ban.enable';
      public const WEBHOOK_DISCORD_BAN_URL = 'webhook.discord.ban.url';
      public const WEBHOOK_DISCORD_KICK_ENABLE = 'webhook.discord.kick.enable';
      public const WEBHOOK_DISCORD_KICK_URL = 'webhook.discord.kick.url';
      
      public const WEBHOOK_STACKAPI_NAME_BOT = 'webhook.stackapi.name_bot';
      public const WEBHOOK_STACKAPI_AVATAR_URL = 'webhook.stackapi.avatar_url';
      public const WEBHOOK_STACKAPI_LOGS_ENABLE = 'webhook.stackapi.logs.enable';
      public const WEBHOOK_STACKAPI_LOGS_URL = 'webhook.stackapi.logs.url';
      public const WEBHOOK_STACKAPI_BAN_ENABLE = 'webhook.stackapi.ban.enable';
      public const WEBHOOK_STACKAPI_BAN_URL = 'webhook.stackapi.ban.url';
      public const WEBHOOK_STACKAPI_KICK_ENABLE = 'webhook.stackapi.kick.enable';
      public const WEBHOOK_STACKAPI_KICK_URL = 'webhook.stackapi.kick.url';

      public const TPS_PROTECTION_MIN_TPS = 'tps_protection.min_tps';
      public const TPS_PROTECTION_LAG_THRESHOLD = 'tps_protection.lag_threshold';
      public const TPS_PROTECTION_RECOVER_MILLIS = 'tps_protection.recover_millis';

      public const CHECK_SETTINGS_VIOLATION_RESET_INTERVAL = 'check_settings.violation_reset_interval';
      public const CHECK_SETTINGS_MAX_CPS = 'check_settings.max_cps';

      public const DATABASE_SQLITE_ENABLE = 'database.sqlite.enable';
      public const DATABASE_SQLITE_HOST = 'database.sqlite.host';
      public const DATABASE_SQLITE_PORT = 'database.sqlite.port';
      public const DATABASE_SQLITE_DATABASE_NAME = 'database.sqlite.database_name';
      public const DATABASE_SQLITE_AUTH_ENABLE = 'database.sqlite.auth.enable';
      public const DATABASE_SQLITE_AUTH_USERNAME = 'database.sqlite.auth.username';
      public const DATABASE_SQLITE_AUTH_PASSWORD = 'database.sqlite.auth.password';
      public const DATABASE_SQLITE_AUTH_DATABASE_NAME = 'database.sqlite.auth.database_name';

      public const DATABASE_MYSQL_ENABLE = 'database.mysql.enable';
      public const DATABASE_MYSQL_HOST = 'database.mysql.host';
      public const DATABASE_MYSQL_PORT = 'database.mysql.port';
      public const DATABASE_MYSQL_DATABASE_NAME = 'database.mysql.database_name';
      public const DATABASE_MYSQL_AUTH_ENABLE = 'database.mysql.auth.enable';
      public const DATABASE_MYSQL_AUTH_USERNAME = 'database.mysql.auth.username';
      public const DATABASE_MYSQL_AUTH_PASSWORD = 'database.mysql.auth.password';
      public const DATABASE_MYSQL_AUTH_DATABASE_NAME = 'database.mysql.auth.database_name';

      public const ADVANCED_CHECK_PROCESSOR = 'advanced.check_processor';
}