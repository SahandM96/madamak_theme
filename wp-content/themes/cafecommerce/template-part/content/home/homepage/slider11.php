        <section id="enamad" class="mt-5" style="display:<?php global $cafe; echo $cafe['section_slider_show_11']; ?>">
            <div class="container">
                <div class="row">
                    <div class="col text-right">
                        <div style="display:<?php global $cafe; echo $cafe['slider11_settings']; ?>">
							<?php global $cafe; if ($cafe['slider_enamad_samandehi']==null):?>
								<a href="#" rel="noopener">
									<img src="<?php bloginfo('template_directory'); ?>/assets/img/enamad.png" class="img-fluid" />
								</a>
								<a href="#" rel="noopener">
									<img src="<?php bloginfo('template_directory'); ?>/assets/img/neshan.jpg" class="img-fluid" />
								</a>
							<?php else: ?>
								<?php  global $cafe;
									foreach($cafe['slider_enamad_samandehi'] as $slide) {
										echo '<a href="' . $slide['url'] . '" target="_blank" rel="noopener">'; 
										echo '<img class="img-fluid" src="' . $slide['image'] . '"></a>'; 
									}
								?>
							<?php endif; ?>
                        </div>
						</div>
                    </div>
                </div>
            </div>
        </section>