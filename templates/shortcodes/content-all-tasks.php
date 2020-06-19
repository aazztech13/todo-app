<div class="col-md-4">
    <div class="card" style="margin-bottom: 20px; width: 18rem; max-width: 100%;">
        <div class="card-body">
            <h5 class="card-title"><?php the_title(); ?></h5>
            <p class="card-text"><?php the_excerpt(); ?></p>
            <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read more</a>
        </div>
    </div>
</div>