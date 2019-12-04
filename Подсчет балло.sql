	CREATE TEMPORARY TABLE average 
	SELECT competitor_id,
       	   AVG(score) AS average 
  	  FROM wp_gma_scores 
   	 WHERE competition_id = 1
	 GROUP 
     	BY competitor_id;

CREATE TEMPORARY TABLE scores
SELECT S.competitor_id, AVG(S.score) as average
  FROM wp_gma_scores S
  JOIN average A
  	ON A.competitor_id = S.competitor_id
    
 WHERE S.competition_id = 1
   AND ABS(A.average - S.score) < 3
 GROUP 
    BY S.competitor_id;
    
    
        SELECT  IFNULL(S.average, 0) as average, M.name as name, SP.name as specialty
          FROM wp_gma_competitor C
     
      JOIN wp_gma_musicians_of_user M
        ON M.id = C.musician_id	
      
      JOIN wp_gma_specialty_for_competitor SC
      	ON SC.competitor_id = C.id
        
      JOIN wp_gma_specialty	SP
      	ON SP.id = SC.specialty_id

 LEFT JOIN scores S
	   ON S.competitor_id = C.id 
  	WHERE C.competition_id = 1
    
    GROUP by C.id
    ORDER BY SP.id, C.id
