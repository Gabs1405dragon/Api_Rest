<?php  

define('TOKEN','dkjhdjkfweijfoihfj238932');

if(isset($_GET['token'])){
    $token = $_GET['token'];
    if($token == TOKEN){
        if(isset($_GET['acao'])){
            $acao = $_GET['acao'];
            $pdo = new PDO('mysql:dbname=clientes;host=localhost;port=8634','root','sonw8634');
            if($acao == 'criar_cliente'){
                
                if(isset($_GET['nome'])){
                    $nome = (string)$_GET['nome'];
                    if(!$nome == ''){
                        $criar = $pdo->prepare("INSERT INTO usuarios VALUES (null,?)");
                        if($criar->execute(array($nome))){
                            die(json_encode(array('cadastrar'=>true,'O nome do usuário'=>$nome)));
                        }else{
                            die(json_encode(array('cadastrar'=>false,'erro'=>'Não foi possivel inserir o usuário')));
                        }
                    }else{
                        die(json_encode(array('cadastrar'=>false,'error'=>'Nome vazio.')));
                    }
                 
                }else{
                    die(json_encode(array('cadastrar'=>false,'erro'=>'não existe o nome na url')));
                }
               
              
            }else if($acao == 'atualizar_cliente'){
                if(!isset($_GET['id'])){
                    die(json_encode(array('error'=>'não existe o id do usuário que vai ser alterado.')));
                }
               $id = (int)$_GET['id'];
                if(isset($_GET['val'])){
                    $val = $_GET['val'];
                    $verificar = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
                    $verificar->execute(array($id));
                    if($verificar->rowCount() == 1){
                        $update = $pdo->prepare("UPDATE usuarios SET nome = ? WHERE id = ?");
                        $update->execute(array($val,$id));
                        die(json_encode(array('sucess'=>true,'o ID '.$id.' foi feita a alteracao do nome '.$val)));
                    }else{
                        die(json_encode(array('nao existe esse id no banco'=>false)));
                    }
                }else{
                    die(json_encode(array('error'=>'não existe o val do usuário que vai ser alterado.'))); 
                }
            }else if($acao == 'deletar_cliente'){
                if(!isset($_GET['id'])){
                    die(json_encode(array('error'=>'não existe o id do usuário que vai ser deletado.')));
                }
               $id = (int)$_GET['id'];
               $deletar = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
               $deletar->execute(array($id));
               die(json_encode(array('sucesso'=>true,'deletado'=>$id)));
               
            }else if($acao == 'visualizar_cliente'){
                if(!isset($_GET['id'])){
                    die(json_encode(array('error'=>'não existe o id')));
                }
                $id = (int)$_GET['id'];
                
                    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
                    $sql->execute(array($id));
                    if($sql->rowCount() == 1){
                        $dados = $sql->fetch();
                      
                        echo json_encode($dados);
                       
                    }else{
                        
                        echo json_encode(array('error'=>'Não existe esse id cadastrado')) ;
                        
                    }
             
            }else{
                die('Esse valor da acao esta errada.');
            }
        }else{
            die('não existe a acao para continuar api..');
        }
    }else{
        die('Essa não é a sua api key.');
    }
}else{
    die('Não existe o token da para fazer a requisição da api!!');
}


