
<style>
	.img-thumbnail img{
		width: 100%;
	}
	.img-thumbnail{
		width: 100px;
	}
	.blog-posts article{
		border: 0 !important;
	    border-top: 1px solid #DDD !important;
	    margin-bottom: 24px;
	    padding-bottom: 0;
	    margin-top: 20px;
	    padding-top: 25px;
	}
	.post-meta a:not(.btn){
		color: #0088cc !important;
	}

</style>
<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Cerca</li>
					</ul>
				</div>
			</div>
		</div>
	</div>


	<div class="container" id="main-custom">
		<div class="col-md-9">
			<div class="post-content">
				<h2>Risultati ricerca per &ldquo;<span><?=$searchValue;?></span>&rdquo;</h2>

				<div class="blog-posts">
					<?php if (count($results)): ?>
						<?php foreach ($results as $result): ?>
							<article class="post post-large" style="margin-left: 0">
								<table width="100%">
									<tr>
										<td style="padding: 5px; vertical-align: top; width: 100%">
											<div class="post-content">
												<h2>
													<a href="<?=$result['link'];?>" title="<?=$result['title'];?>">
														<?=$result['title'];?>
													</a>
												</h2>


												<p>
													<?=$this->Text->truncate(
															strip_tags($result['description']),
															300,
															array(
																'ending' => ' ...',
																'exact' => false
																)
															);?>
												</p>

												<div class="post-meta">
													<span></span>
													<a href="<?=$result['link'];?>" class="btn btn-xs btn-primary pull-right">Leggi tutto</a>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</article>
						<?php endforeach; ?>
					<?php else: ?>
						<p>Nessun risultato per &ldquo;<span><?=$searchValue;?></span>&rdquo;</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
	
