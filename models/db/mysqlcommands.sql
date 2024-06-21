-- expired note cleaner event scheduler
CREATE EVENT if not exists expired_note_cleaner ON SCHEDULE EVERY 1 HOUR STARTS CURRENT_TIMESTAMP ON COMPLETION PRESERVE DO
DELETE FROM notes
WHERE
  expiration < UNIX_TIMESTAMP();
-- blank guest note cleaner event scheduler
  CREATE EVENT if not exists blank_guest_note_cleaner ON SCHEDULE EVERY 1 HOUR STARTS CURRENT_TIMESTAMP ON COMPLETION PRESERVE DO
DELETE FROM notes
WHERE
  (
    text IS NULL
    OR text = ' '
  )
  and lastVisited < (UNIX_TIMESTAMP() - 2400)
  and noteOwner = 'guest';
-- blank user public note cleaner event scheduler
  CREATE EVENT if not exists blank_user_public_note_cleaner ON SCHEDULE EVERY 3 HOUR STARTS CURRENT_TIMESTAMP ON COMPLETION PRESERVE DO
DELETE FROM notes
WHERE
  (
    text IS NULL
    OR text = ' '
  )
  and lastVisited < (UNIX_TIMESTAMP() - 3600)
  and noteOwner != 'guest'
  and notePrivacy = '0';
--cp req  delete when time expires
  CREATE EVENT if not exists cpreq_expired_cleaner ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP ON COMPLETION PRESERVE DO
DELETE FROM cpreq
WHERE
  reqDate < (UNIX_TIMESTAMP() - 604800);