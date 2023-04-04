UPDATE hangout SET state = 'reg_closed' WHERE last_register_date > CURRENT_TIMESTAMP AND state = 'reg_open';
UPDATE hangout SET state = 'in_progress' WHERE start_timestamp < CURRENT_TIMESTAMP AND start_timestamp + duration > CURRENT_TIMESTAMP;
UPDATE hangout SET state = 'done' WHERE start_timestamp + duration < CURRENT_TIMESTAMP AND state = 'in_progress';
UPDATE hangout SET state = 'reg_open' WHERE last_register_date < start_timestamp AND CURRENT_TIMESTAMP < start_timestamp;