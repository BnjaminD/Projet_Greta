
-- Extract just the filename from whatever path might be stored
UPDATE user 
SET profile_picture_url = SUBSTRING_INDEX(profile_picture_url, '/', -1) 
WHERE profile_picture_url IS NOT NULL 
AND profile_picture_url != '';

-- Make sure there are no duplicate slashes or incorrect path fragments
UPDATE user
SET profile_picture_url = REPLACE(profile_picture_url, '//', '/')
WHERE profile_picture_url LIKE '%//%';