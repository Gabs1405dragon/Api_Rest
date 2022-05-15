<h2>API REST feita do zero com php | Documentação</h2>
<h3>Nessa documentação vai ser feita uma api rest com requisição <a href="http://devfuria.com.br/php/como-funcionam-os-metodos-get-e-post/">GET</a> e o retorno no formato <a href="https://www.json.org/json-en.html">Json</a>.</h3>
<p>Vamos começar primeiro a criar uma <b>api key</b> colocando em uma constante <a href="https://www.php.net/manual/en/function.define.php">define</a>.<p>
  <p>O valor da constante vai ser um indentificador unico que você vai criar. fazendo isso agora vamos verificar se existe o <a href="https://www.php.net/manual/en/reserved.variables.globals.php">$_GET</a> token com a função <a href="https://www.php.net/manual/en/function.isset.php">isset()</a> se caso não existir vai aparece uma 
mensagem de erro.</p>
<p>Depois verificar se o token é igual o indentificador unico que você colocou na constante.</p>
<p>Agora verificar se existe <b>"acao"</b> na query url. Essa query vai ser o ponto chave da api para fazer a ação da api.se existir agora nós vamos fazer uma conexão com o banco de dados
usando a class do <a href="https://www.php.net/manual/en/intro.pdo.php">PDO</a> para fazer a conexão com o banco</p>
<p>Mais antes disso nós temos que criamos o banco de dados o nome do banco vai ser clientes e vai ter a tabela usuários para cadastrar os clientes.a tabela vai ter dois campos um com o <b>id</b> para ficar 
incrementando todos os novos dados que são inseridos ,e o outro campo é o <b>nome</b> onde vai ser o nome de todos os usuários cadastrados.</p>

        create database clientes;
        use clientes;
        create table usuarios(
            id int unsigned not null auto_increment,
            nome varchar(45) not null,
            primary key (id)
        );
       
  <h3>Agora fazer as funcionalidades da api que van ser <b>(Criar um usuário,deletar usuário ,atualizar o usuário e visualizar o usuário)</b>.</h3>
<ol>
<li>Cadastrar o usuário na tabela.
  <ul>
    <li>Verificar se o valor da query url acao é </b>"criar_cliente"</b>.</li>
    <li>Verificar se a existi a query url <b>nome</b> .</li>
    <li>Verificar se o nome está vázio.</li>
    <li>Inserir no banco de dados o usuário com o <a href="https://www.w3schools.com/sql/sql_insert.asp">INSERT</a> onde o campo nome vai ser o valor do nome que foi passado na query url nome.</li>
    <li>Se passou na validação vai ser retornada a mensagem em formato json com a função <a href="https://www.php.net/manual/pt_BR/function.json-encode.php">json_encode()</a>.</li>
  </ul>
</li>
<li>Deletar o usuário da tabela.
  <ul>
    <li>Verificar se existi a query url <b>'id'</b>.</li>
    <li>Deletar o usuário do banco com <a href="https://www.w3schools.com/sql/sql_delete.asp">DELETE</a> onde o id do usuário vai ser o valor que você deu na query url.</li>
  </ul>
</li>
<li>Atualizar o usuário.
  <ul>
    <li>Verificar se existi a query url <b>'id'</b>.</li>
    <li>Verificar se existi a query url <b>'val'</b>.</li>
    <li>Verificar se existi o valor do id que foi passado na query url no banco de dados usando o <a href="https://www.w3schools.com/sql/sql_select.asp">"SELECT"</a>.</li>
    <li>Agora fazer a alteração com o <a href="https://www.w3schools.com/sql/sql_update.asp">"UPDATE"</a> onde o valor do campo nome vai ser o valor que você passou no <b>var</b> da query url.</li>
  </ul>
</li>
<li>Puxar a informação de um usuário individual.
  <ul>
    <li>Verificar se existi a query url <b>'id'</b>.</li>
    <li>Usar o <b>"SELECT"</b> onde o campo <b>Id</b> vai ser o valor do que você passou no id da query url</li>
    <li>Se passar na validação os dados van ser puxados em formato <b>json</b></li>
  </ul>
</li>
</ol>
<h3>O código do completo é esse que esta mostrando em baixo!!</h3>

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
          
          
<h3>Muito obrigado por ter lido o código até aqui :)</h3>
<p>Minhas redes socias</p>
<ul>
  <li><a href="https://www.instagram.com/gabs1405henrique/">Instagram</a></li>
  <li><a href="https://github.com/Gabs1405dragon">GitHub</a></li>
  <li><a href="https://www.linkedin.com/in/gabriel-h-assis-de-souza-60b496207/">Linkedin</a></li>
</ul>
