								<div id="last-boxes">
									<? if ($this->requestAction('/pages/isDisabled/57') == 0): ?>
									<div class="box-bottom">
										<a href="http://www.midlandsport.it/contenuti/113/amatoriale-f-i-g-c" title="">
											<img src="/img/amatoriale.jpg" width="212" height="280" alt="diventare arbitro" />
											<span>Amatoriale FIGC</span>
										</a>
									</div>
									<? endif; ?>
									<? if ($this->requestAction('/pages/isDisabled/86') == 0 && 1 == 0): ?>
									<div class="box-bottom">
										<a href="/contenuti/86/rassegna-stampa" title="rassegna stampa">
											<img src="/img/website/rassegna-stampa.jpg" width="212" height="160" alt="rassegna stampa" />
											<span>Rassegna stampa</span>
										</a>
									</div>
									<? endif; ?>
									
									
									<div class="box-bottom" id="twitter-badge">
									<!--	<script src="http://widgets.twimg.com/j/2/widget.js"></script>
										-->
										<!--
										<script src="/js/twt-widget.js"></script>-->

<a class="twitter-timeline"  href="https://twitter.com/MidlandSport"  data-widget-id="344721946916818944" data-chrome="nofooter">Tweets by @MidlandSport</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


									</div>									
									
									<div class="box-bottom" id="facebook-badge" style="border-bottom: 1px solid #AAA;">
													 <iframe src="//www.facebook.com/plugins/likebox.php?href=https://www.facebook.com/midlandsportfirenze&amp;width=250&amp;height=275&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color=transparent&amp;header=false&amp;appId=251949691505563&css=<?=urlencode('http://www.midlandsport.it/css/layout_fb.css?1');?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:273px;" allowTransparency="true"></iframe>
									</div>
								
 									<div id="newsletter-box" class="box-bottom">
 										<? if ($_SERVER['REQUEST_URI'] != "/contatti"):?>
 										
 										<a href="/contenuti/107/scuola-c5" title=""><img src="/img/banner-scuola.jpg" width="217" alt="" height="280" />		<span style="width: 206px;">La scuola calcio a 5</span>
										
									</a>
 										<? else: ?>
                                        <h2>Newsletter</h2>
                                        <p style="padding-top: 5px;">Iscriviti al servizio di newsletter</p>
                                        <form id="newsletter-subscription" method="get" action="javascript:;">
                                            <input style="margin-top: 5px;" type="text" class="text" value="indirizzo email..." />
                                            <input style="margin-top: 5px;" type="submit" class="submit" value="Invia" />
                                            <div class="clear"></div>
                                            <a style="margin-top: 5px;" class="checkbox-privacy" href="javascript:;" title=""><img data-value="0" src="/img/website/bg-checkbox.png" width="18" height="18" alt="" /></a>
                                            <p style="padding-top: 5px;" class="view-privacy">Presto il consenso al <a rel="timmygallery" link="/img/timmybox/blank.gif" url="/pages/privacy" href="javascript:;" title="trattamento dei dati personali">trattamento dei dati personali</a></p>                                   
                                            <div class="clear"></div>
                                            <div style="margin-top: 0px;" class="error-message"></div>
                                            <div style="margin-top: 0px;" class="ok-message"></div>
                                        </form>
                                        
                                        <h2>Buon compleanno a...</h2>
                                        
                                        <div class="compleanni" style="overflow-y: scroll; max-height: 100px;">
                                        
                                        	<p style="padding-left: 5px; text-shadow: 1px 1px #bbb;">
                                        	
                                        		<? foreach ($compleanni as $compleanno): ?>
                                        		
                                        			<span style="color: #fff;"><?=$this->Text->truncate($compleanno['nome'],25,array('ending' => '...'));?></span><br />
                                        		
                                        		<? endforeach; ?>
                                        	
                                        	</p>
                                        
                                        </div>
                                   		<? endif; ?>
                                        
                                    </div><!-- close newsletter-box -->

                               

									<div class="clear"></div>
									
				
									
									
								</div><!-- close last-boxes -->
