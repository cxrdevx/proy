<?php
class Login extends CI_Controller {

   function ingresar(){

      if(!isset($_POST['correo'])){
         $data['error']="El campo correo es obligatorio";
         $this->index($data);
      }else{                    
         $this->form_validation->set_rules('correo','e-mail','required|valid_correo|trim|xss_clean');      
         $this->form_validation->set_rules('password','password','required|trim|xss_clean');
         if(($this->form_validation->run()==FALSE)){ 
            $this->index();
         }else{                                      
            $this->load->model('usuario_model');
            $ExisteUsuarioyPassoword=$this->usuario_model->ValidarUsuario($_POST['correo'],$_POST['password']);  
            if($ExisteUsuarioyPassoword){  
               echo "Validacion Ok";
               exit;   
            }else{ 
               $data['error']="Correo o password incorrecta, por favor vuelva a intentar";
               $this->index($data);
            }
         }
      }
   }
   

   function registro()
   {
      if(!isset($_POST['correo'])){
         $this->form_validation->set_rules('correo','Correo','required|valid_correo|trim|xss_clean');
         $this->form_validation->set_rules('password','Password','required|trim|xss_clean|sha1');
         
         $this->form_validation->set_message('required', 'El %s es requerido');
         $this->form_validation->set_message('valid_correo', 'El %s no es válido');
         
         //SI ALGO NO HA IDO BIEN NOS DEVOLVERÁ AL INDEX MOSTRANDO LOS ERRORES
         if($this->form_validation->run()==FALSE)
         {
            $this->load->view('headerTemplate');
            $this->load->view('registroTemplate');
            $this->load->view('footerTemplate');
         }else{
         //EN CASO CONTRARIO PROCESAMOS LOS DATOS
            $correo = $this->input->post('correo');
            $password = $this->input->post('password');
                        //ENVÍAMOS LOS DATOS AL MODELO CON LA SIGUIENTE LÍNEA
            $this->load->model('usuario_model');
            $insert = $this->usuario_model->nuevoUsuario($correo,$password);
            //si el modelo nos responde afirmando que todo ha ido bien, envíamos un correo
            //al usuario y lo redirigimos al index, en verdad deberíamos redirigirlo al home de
            //nuestra web para que puediera iniciar sesión
            //$this->correo->from('aqui el correo que quieres que envíe los datos', 'uno-de-piera.com');
            //$this->correo->to($correo);
            //super importante, para poder envíar html en nuestros correos debemos ir a la carpeta 
            //system/libraries/correo.php y en la línea 42 modificar el valor, en vez de text debemos poner html
            //$this->correo->subject('Bienvenido/a a uno-de-piera.com');
            //$this->correo->message('<h2>' . $nombre . ' gracias por registrarte en uno-de-piera.com</h2><hr><br><br>
            //Tu nombre de usuario es: ' . $nick . '.<br>Tu password es: ' . $password);
            //$this->correo->send();
            echo "registrado";
         }
      }else{
         $this->load->view('headerTemplate');
         $this->load->view('registroTemplate');
         $this->load->view('footerTemplate');   
      }
   }


   function index($data=null){
      $this->load->view('loginTemplate',$data);
   }
}
?>