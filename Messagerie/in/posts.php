<div style="padding: 15px; border-radius: 10px;max-width: auto;margin:  5px;box-shadow: 0 2px 4px rgba(0, 0, 0, 0.8) ">
  <div>
    <div style="color: #405d9b;font-weight: bolder">
        <?php echo $Row_user['prenom'] . " " . $Row_user['nom']; ?>
    </div>
    <br/>
    <div style="font-weight: bold">
        <?php echo $Row['titre'] ?>
    </div>
    <br/>
        <?php echo $Row['post'] ?>
    <br /><br/>
    <!--<a href="">Like</a> . <a href="">Comment</a> .-->

    <span style="color: #999;">
      <?php echo $Row['date'] ?>
      <br />
    </span>

  </div>
</div>