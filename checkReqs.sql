CREATE TRIGGER `checkReqs` BEFORE INSERT ON `contains`
 FOR EACH ROW BEGIN
 DECLARE playerLevel INT;
 DECLARE spellLevel INT;
 SELECT P.level
 INTO playerLevel
 FROM contains
 JOIN Spellbook AS SB ON SB.spellbook_id = contains.spellbook_id
 JOIN Player AS P ON P.player_id = SB.player_id
 WHERE SB.spellbook_id = NEW.spellbook_id;
 SELECT S.level
 INTO spellLevel
 FROM Spell AS S
 WHERE S.spell_id = NEW.spell_id;
 IF (playerLevel<spellLevel) THEN
 SIGNAL SQLSTATE '45000'
 SET MESSAGE_TEXT = 'Your level is too low to add this spell';
 END IF;
END