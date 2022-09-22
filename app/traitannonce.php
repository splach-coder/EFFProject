<?php
    try{
        
        session_start();
        require_once 'db.php';
        include '../assets/php/functions.php';
        
        $annonceId = $_POST['id'];
        
        if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email'])){
            
            //delete definitvely
            if(isset($_POST['delete']) && $_POST['delete'] == 'delete'){
               try {
                    $idInfos = getSingleValue($conn, "SELECT id_infos_Generales FROM `announcer` WHERE `id_announcer` = $annonceId");

                    
                    $sqll = "DELETE FROM `announce_views` WHERE id_announce = $annonceId";
                    $conn->exec($sqll);

                    
                    //delete from annonces defintively
                    $sql = "DELETE FROM `announcer` WHERE `id_announcer` = $annonceId";
                    $conn->exec($sql);
                
                    $query = "SELECT id_image, img FROM images WHERE id_infos_Generales = $idInfos";
                    $stmt = $conn->query($query);
                    while ($row = $stmt->fetch()) {
                        $id = $row['id_image'];
                        $imagename = $row['img'];
                        $targetpath = "../assets/images/".$imagename;
                        
                        if (file_exists($targetpath)) {
                          if(unlink($targetpath)){
                            $sql7 = "DELETE FROM `images` WHERE id_infos_Generales = $idInfos";
                            $conn->exec($sql7);
                          }
                        }
                    }

                    //delete from annonces defintively
                    $sql8 = "DELETE FROM `infos_generales` WHERE `id_infos_Generales` = $idInfos";
                    $conn->exec($sql8);

                    
                    
               } catch (Exception $e) {
                    echo $e->getMessage();
               }
            }else{

                //delete from annonces
                $sql = "UPDATE `announcer` SET status = 'supprimer' WHERE `id_announcer` = $annonceId";
                $conn->exec($sql);
                echo true;
            }            
        }else{
            echo false;
        }    
        
    }catch(Exception $e){
        echo $e->getMessage();
    }
    


    


    

    
