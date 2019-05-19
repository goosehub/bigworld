-- Get highly flagged messages not already removed
SELECT * FROM `message` WHERE message != '[removed]' ORDER BY `message`.`report_count` DESC;

-- Get highly flagged IPs
SELECT ip, SUM(report_count) as sum_report_count FROM `message` GROUP BY ip ORDER BY `sum_report_count` DESC

-- Get highly flagged users
SELECT user_key, SUM(report_count) as sum_report_count FROM `message` GROUP BY user_key ORDER BY `sum_report_count` DESC

-- Remove content of message 
UPDATE message SET message = '[removed]' WHERE id = 1;