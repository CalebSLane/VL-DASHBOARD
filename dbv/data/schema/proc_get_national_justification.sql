DROP PROCEDURE IF EXISTS `proc_get_national_justification`;
DELIMITER //
CREATE PROCEDURE `proc_get_national_justification`
(IN filter_year INT(11), IN from_month INT(11), IN to_month INT(11))
BEGIN
  SET @QUERY =    "SELECT
                    `vj`.`name`,
                    SUM((`vnj`.`tests`)) AS `justifications`
                FROM `vl_national_justification` `vnj`
                JOIN `viraljustifications` `vj` 
                    ON `vnj`.`justification` = `vj`.`ID`
                WHERE 1";

    IF (from_month != 0 && from_month != '') THEN
      IF (to_month != 0 && to_month != '') THEN
            SET @QUERY = CONCAT(@QUERY, " AND `vnj`.`year` = '",filter_year,"' AND `vnj`.`month` BETWEEN '",from_month,"' AND '",to_month,"' ");
        ELSE
            SET @QUERY = CONCAT(@QUERY, " AND `vnj`.`year` = '",filter_year,"' AND `vnj`.`month`='",from_month,"' ");
        END IF;
    ELSE
        SET @QUERY = CONCAT(@QUERY, " AND `vnj`.`year` = '",filter_year,"' ");
    END IF;

    SET @QUERY = CONCAT(@QUERY, " GROUP BY `vj`.`name` ");

    PREPARE stmt FROM @QUERY;
    EXECUTE stmt;
END //
DELIMITER ;
