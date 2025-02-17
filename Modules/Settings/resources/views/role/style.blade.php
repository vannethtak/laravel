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
</style>
