CREATE TEMPORARY TABLE average 
	SELECT competitor_id,
       	   AVG(score) AS average 
  	  FROM wp_gma_scores 
   	 WHERE competition_id = 7
	 GROUP 
     	BY competitor_id;

CREATE TEMPORARY TABLE scores
SELECT S.competitor_id, AVG(S.score) as average 
  FROM wp_gma_scores S
  JOIN average A
  	ON A.competitor_id = S.competitor_id
    
 WHERE S.competition_id = 7
   AND ABS(A.average - S.score) < 3
 GROUP  
    BY S.competitor_id;

SELECT C.id AS id,
           IFNULL(S.average, 0) as average, 
           MU.name AS name,
           CCU.source AS sourceUrl,
           CCF.source AS sourceFile,
           CASE 
            WHEN S_MAIN.name IS NOT NULL THEN CONCAT(S_MAIN.name, ' (', S_CURRENT.name, ')') 
            ELSE CONCAT(S_CURRENT.name,  ' (', N.name, ')') END AS specialty,
           C.compositions AS compositions,
           C.city AS city,
           C.telephone AS tel,
           C.age_category AS ageCategory
    
      FROM wp_gma_competitor C

      JOIN wp_gma_musicians_of_user MU
        ON MU.id = C.musician_id

      JOIN wp_gma_specialty_for_competitor SC
        ON SC.competitor_id = C.id

      JOIN wp_gma_specialty S_CURRENT
        ON S_CURRENT.id = SC.specialty_id
      
 LEFT JOIN wp_gma_specialty S_MAIN
        ON S_MAIN.id = S_CURRENT.parent_id

 LEFT JOIN wp_gma_nomination_for_competitor NC
        ON NC.competitor_id = C.id

 LEFT JOIN wp_gma_nomination N
        ON N.id = NC.nomination_id

      JOIN wp_gma_competitor_content CCU 
        ON CCU.competitor_id = C.id 
        AND CCU.type = 0

LEFT JOIN wp_gma_competitor_content CCF 
        ON CCF.competitor_id = C.id 
        AND CCF.type = 1

 LEFT JOIN scores S
	   ON S.competitor_id = C.id 
    
     WHERE C.competition_id = 7
       AND C.isConfirm = 1
       AND IFNULL(S_MAIN.id, S_CURRENT.id) = 6
        
       GROUP by C.id

       ORDER BY specialty, N.id, ageCategory