
-- This query updates all profile picture URLs in the user table
-- It only updates records where the profile_picture_url field is not empty
-- It prepends the proper path if it's not already there

UPDATE user 
SET profile_picture_url = CONCAT('../../images/profile/', profile_picture_url) 
WHERE profile_picture_url IS NOT NULL 
AND profile_picture_url != '' 
AND profile_picture_url NOT LIKE '%../../images/profile/%';

-- Alternatively, if you want to standardize all paths (remove any existing paths first)
-- Uncomment the following:

-- UPDATE user 
-- SET profile_picture_url = CONCAT('../../images/profile/', 
--     SUBSTRING_INDEX(profile_picture_url, '/', -1)) 
-- WHERE profile_picture_url IS NOT NULL 
-- AND profile_picture_url != '';