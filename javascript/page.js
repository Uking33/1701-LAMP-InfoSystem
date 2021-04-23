function addNum(i,currentPage,psize,num,head){
	if(currentPage==i)
		return "<span style='margin-left:5px;margin-right:5px;'><input class='btn_checked' type='button' value='"+i+"'></span>";
	else
		return "<span style='margin-left:5px;margin-right:5px;'><input class='btn btn-default' type='button' value='"+i+"' onClick=\"goPage("+(i)+","+psize+","+num+","+head+")\"></span>";
}
function goPage(pno,psize,num,head){
	if(!head){head=false;} 
    var content = document.getElementById("content");
    var pageSize = psize;//每页显示行数
    var currentPage = pno;//当前页数
    var totalPage = 0;//总页数
    if(num/pageSize > parseInt(num/pageSize)){
    	totalPage=parseInt(num/pageSize)+1;   
    }else{
        totalPage=parseInt(num/pageSize);
    }
    var startRow = (currentPage - 1) * pageSize+1;//开始显示的行
    var endRow = currentPage * pageSize;//结束显示的行
    endRow = (endRow > num)? num : endRow;
    //遍历显示数据实现分页
    if(head){
        for(var i=1;i<(num+2);i++){    
            var irow = content.rows[i-1];
            if(i==1 || (i>=startRow+1 && i<=endRow+1)){
                irow.style.display = "";    
            }else{
                irow.style.display = "none";
            }
        }
    }
    else{
        for(var i=1;i<(num+1);i++){    
            var irow = content.rows[i-1];
            if(i>=startRow && i<=endRow){
                irow.style.display = "";    
            }else{
                irow.style.display = "none";
            }
        }
    }
    
    var tempStr = "<span>";
    if(currentPage>1){
        tempStr += "<span style='margin-left:5px;margin-right:5px;'><input class='btn btn-default' type='button' value='上一页' onClick=\"goPage("+(currentPage-1)+","+psize+","+num+","+head+")\"></span>";
    }
    if(totalPage<=4){
	    for(var i=1;i<=totalPage;i++){
	    	tempStr += addNum(i,currentPage,psize,num,head);
	    }
	}
    else{
    	if(currentPage<=2){
		    for(var i=1;i<=3;i++){
		    	tempStr += addNum(i,currentPage,psize,num,head);
		    }
		    tempStr += "<label>...</label>";
		    tempStr += "<span style='margin-left:5px;margin-right:5px;'><input class='btn btn-default' type='button' value='"+totalPage+"' onClick=\"goPage("+(totalPage)+","+psize+","+num+","+head+")\"></span>";
	    }
    	else if(currentPage>=totalPage-1){tempStr += "<span style='margin-left:5px;margin-right:5px;'><input class='btn btn-default' type='button' value='"+1+"' onClick=\"goPage("+(1)+","+psize+","+num+","+head+")\"></span>";
		    tempStr += "<label>...</label>";
		    for(var i=totalPage-2;i<=totalPage;i++){
		    	tempStr += addNum(i,currentPage,psize,num,head);
		    }
		}
    	else{
    		tempStr += "<span style='margin-left:5px;margin-right:5px;'><input class='btn btn-default' type='button' value='"+1+"' onClick=\"goPage("+(1)+","+psize+","+num+","+head+")\"></span>";
    		tempStr += "<label>...</label>";
    		for(var i=currentPage-1;i<=currentPage+1;i++){
		    	tempStr += addNum(i,currentPage,psize,num,head);
		    }
    		tempStr += "<label>...</label>";
    		tempStr += "<span style='margin-left:5px;margin-right:5px;'><input class='btn btn-default' type='button' value='"+totalPage+"' onClick=\"goPage("+(totalPage)+","+psize+","+num+","+head+")\"></span>";
    	}
    }
    if(currentPage<totalPage){
        tempStr += "<span style='margin-left:5px;margin-right:5px;'><input class='btn btn-default' type='button' value='下一页' onClick=\"goPage("+(currentPage+1)+","+psize+","+num+","+head+")\"></span>";
    }
    tempStr += "</span>"
    tempStr += "<span>&nbsp;&nbsp;共"+totalPage+"页 / "+num+"条</span>";
    document.getElementById("barcon").innerHTML = tempStr;
}