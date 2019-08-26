function createFlowPrompt(){
    modalPrompt("Please enter your new flow's name:","",
        function(flowname){
            if(flowname === null || flowname === "") {
                //user did not enter anything
                txt = "User cancelled the prompt.";
            }
            else{
                window.location = "create_flow.php?name="+encodeURIComponent(flowname);
            }
        },
        function(value){
            //user clicked cancel
        });
}