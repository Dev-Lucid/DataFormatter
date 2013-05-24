var dfm={'hooks':{}};

dfm.object=function(){
	this.actions = [];
}

dfm.object.ifnull=function(nullVal){
	this.actions.push(['ifnull',nullVal]);
}

dfm.object.ifblank=function(blankVal){
	this.actions.push(['ifblank',blankVal]);
}

dfm.object.int=function(){
	this.actions.push(['int']);
}

dfm.object.float=function(){
	this.actions.push(['float']);
}

dfm.construct=function(){
	var obj = new dfm.object;
	return obj;
}

dfm.object.prototype.apply=function(inData){
	var out = inData;
	for(var i=0;i<this.actions.length;i++){
		switch(this.actions[0]){
			case 'ifnull':
				out = (out+'' == 'undefined')?this.actions[1]:out;
				break;
			case 'ifblank':
				out = (out+'' == '')?this.actions[1]:out;
				break;
			case 'int':
				out = parseInt(out);
				break;
			case 'float':
				out = parseFloat(out);
				break;
			case 'add':
				out += this.actions[1];
				break;
			case 'subtract':
				out -= this.actions[1];
				break;
			default:
				if(typeof(dfm.hooks[this.actions[0]]) == 'function'){
					out = dfm.hooks[this.actions[0]](out);
				}else{
					alert('Could not find formatting function: '+this.actions[0]);
				}
				break;
		}
	}
	return out;
};
