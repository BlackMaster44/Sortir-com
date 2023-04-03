UPDATE hangout SET state = 'reg_closed' WHERE last_register_date > CURRENT_TIMESTAMP;
UPDATE hangout SET state = 'done' WHERE start_timestamp + duration;
UPDATE hangout SET state = 'in_progress' WHERE start_timestamp = CURRENT_TIMESTAMP;
UPDATE hangout SET state = 'created' WHERE start_timestamp > CURRENT_TIMESTAMP
UPDATE hangout SET state = 'reg_open' WHERE last_register_date < start_timestamp;