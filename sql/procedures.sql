USE xalencia_task_db;

DELIMITER //

DROP PROCEDURE IF EXISTS create_user;
CREATE PROCEDURE create_user(
    IN first_name VARCHAR(100),
    IN last_name VARCHAR(100),
    IN email VARCHAR(150)
)
BEGIN
  INSERT INTO users (first_name, last_name, email)
  VALUES (first_name, last_name, email);
END //

DROP PROCEDURE IF EXISTS get_user_by_id;
CREATE PROCEDURE get_user_by_id(IN id INT)
BEGIN
  SELECT * FROM users WHERE users.id = id;
END //

DROP PROCEDURE IF EXISTS get_all_users;
CREATE PROCEDURE get_all_users()
BEGIN
  SELECT * FROM users;
END //

DROP PROCEDURE IF EXISTS update_user;
CREATE PROCEDURE update_user(
    IN id INT,
    IN first_name VARCHAR(100),
    IN last_name VARCHAR(100),
    IN email VARCHAR(150)
)
BEGIN
  UPDATE users
  SET first_name = first_name,
      last_name = last_name,
      email = email,
      updated_at = CURRENT_TIMESTAMP
  WHERE users.id = id;
END //

DROP PROCEDURE IF EXISTS delete_user;
CREATE PROCEDURE delete_user(IN id INT)
BEGIN
  DELETE FROM users WHERE users.id = id;
END //

DELIMITER ;
