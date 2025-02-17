<style>

/*Checkbox tree */
ul.checktree li {
    border-left: 1px dashed #ccc;
    margin: 1px;
}
ul.checktree, .checktree ul{
    list-style-type: none;
    margin: 3px;
}
ul.checktree li:before {
    height: 1em;
    width: 12px;
    border-bottom: 1px dashed #ccc;
    content: "";
    display: inline-block;
    top: -0.3em;
}
ul.checktree li {
    border-left: 1px dashed #ccc;
    margin: 1px;
}
ul.checktree li:last-child:before { border-left: 1px dashed #ccc;  }
ul.checktree li:last-child {border-left: none; }
ul.checktree li:last-child:before {
    border-left: 1px dashed #ccc;
}
ul.checktree li:before {
    height: 1em;
    width: 12px;
    border-bottom: 1px dashed #ccc;
    content: "";
    display: inline-block;
    top: -0.3em;
}


/* account profile */
.container {
    background-color: #f8f9fa;
}
.profile-wrapper {
    min-height: 75vh;
}
.profile {
    width: 100%;
}
.bg_cover {
    width: 100vw;
    height: 75vh;
    background-size: cover;
    background-position: center;
}
/* .page-inner {
  padding: 0;
  margin: 0;
  width: 100%;
  height: 100%;
} */
.profile .profile-header .profile-cover-photo {
  width: 100%;
  height: 230px;
  border-radius: 8px;
}
.form-control:disabled, .form-control[readonly] {
    background: none !important;
    border-color: none !important;
    color: rgb(26, 26, 26);
}

.profile-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.profile-photo img {
    width: 160px;
    height: 160px;
    object-fit: cover;
    border-radius: 50%; /* Optional: Makes it a circular profile picture */
}

.upload-icon {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    padding: 8px;
    cursor: pointer;
    display: none;
}

.profile-photo:hover .upload-icon {
    display: block;
}
</style>
