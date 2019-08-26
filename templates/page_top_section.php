<div class="row wrapper border-bottom page-heading" style="background:#F9FAFC;">
    <div class="col-sm-4">
    				  <!--<h2><?php //echo $this_page_title;?></h2>-->
    					          <?php
                            echo "<ol class='breadcrumb' style='margin-top:20px;'>";

                            switch (basename($_SERVER['PHP_SELF'], '.php')) {

                            	case "profile":
                                    echo '<li>
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li class="active">
                                            <strong>Profile</strong>
                                        </li>';
                                    break;
                                case "integrations":
                                    echo '<li>
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li class="active">
                                            <strong>Integrations</strong>
                                        </li>';
                                    break;
                                case "wizard":
                                    echo '<li>
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li class="active">
                                            <strong>Setup Your Bot</strong>
                                        </li>';
                                    break;
                                case "billing":
                                    echo '<li>
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li class="active">
                                            <strong>Billing</strong>
                                        </li>';
                                    break;

                                default:
                                    if('profile.php' == basename($_SERVER['PHP_SELF']) )
                                    {
                                        echo '<li>
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li class="active">
                                            <strong>Profile</strong>
                                        </li>';
                                    }
                            }
                            echo "</ol>";
                            ?>
    </div>                
	<div class="col-sm-8">
			          <div class="title-action"><?php

                          //if(isset($msg) && $msg !='Success, Saved your profile details' && $msg !='Error, Please make sure that you entered your password twice the same'){echo '<a href="" class="btn btn-primary"><strong>'.$msg.'</strong></a>';}
                          if(basename($_SERVER['PHP_SELF'], '.php')){
                              $fb = new Facebook\Facebook([
                                  'app_id' => SB_FB_APP,
                                  'app_secret' => SB_FB_SECRET,
                                  'default_graph_version' => 'v2.8',
                              ]);
                              $helper = $fb->getRedirectLoginHelper();
                              $this_url = 'https://'. $_SERVER["SERVER_NAME"]. '/profile.php';
                              $loginUrl = $helper->getLoginUrl($this_url,array('scope'=>'read_page_mailboxes,manage_pages,pages_messaging'));
                              echo '<a href="'.$loginUrl.'" class="btn btn-primary" style="display: none;">Refresh pagedata </a>';
                          }
                          ?>
                      </div>
    </div>
</div>