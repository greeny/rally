# Rally configurator

An app for rally team management.

## Installation

- run `composer install`
- copy `config/local.example.neon` to `config/local.neon`, and change user/password for database
- run `bin/console o:s:u --force --complete`
- run `bin/console o:generate-proxies`
- set up a server pointing to `www` directory

## Initial data

After database is set up, you need to run these commands to generate some common data:

```sql
INSERT INTO `language` (`id`, `code`, `name`) VALUES
(1,	'en',	'English'),
(2,	'cs',	'Čeština');

INSERT INTO `role` (`id`, `type`, `min`, `max`) VALUES
(1,	'racer',	1,	3),
(2,	'passenger',	1,	3),
(3,	'technician',	1,	2),
(4,	'manager',	1,	1),
(5,	'photographer',	0,	1);

INSERT INTO `role_translation` (`id`, `language_id`, `role_id`, `name`) VALUES
(1,	1,	1,	'Racer'),
(2,	2,	1,	'Závodník'),
(3,	1,	2,	'Passenger'),
(4,	2,	2,	'Spolujezdec'),
(5,	1,	3,	'Technician'),
(6,	2,	3,	'Technik'),
(7,	1,	4,	'Manager'),
(8,	2,	4,	'Manažer'),
(9,	1,	5,	'Photographer'),
(10,	2,	5,	'Fotograf');
```

## Cron

- `bin/console app:calendar:import` imports data from remote calendar
- `bin/console app:calendar:clear-log` clears old log data from imports

Set up two crons for the above commands in rates you need.
