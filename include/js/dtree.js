// Node object
function Node(id, pid, name, url, title, target, icon, iconOpen, open, daysLeft) {
    this.id = id;
    this.pid = pid;
    this.name = name;
    this.url = url;
    this.title = title;
    this.target = target;
    this.icon = icon;

    this.daysLeft = daysLeft;

    this.iconOpen = iconOpen;
    this._isOpen = open || false;
    this._currentNode = false;
    this._lastNode = false;
    this._firstNode = false;
    this._autoIncrement = 0;
    this._parentNode;
}


// Tree object
function dTree(objName) {
    this.config = {
        target: null,
        folderLinks: true,
        useSelection: false,
        useCookies: true,
        useLines: true,
        useIcons: true,
        closeSameLevel: false,
        inOrder: false
    };
    this.icon = {
        root: 'templates/standard/theme/standard/images/symbols/empty.gif',
        folder: 'templates/standard/theme/standard/images/symbols/folder.gif',
        folderOpen: 'templates/standard/theme/standard/images/symbols/folderopen.gif',
        node: 'templates/standard/theme/standard/images/symbols/page.gif',
        empty: 'templates/standard/theme/standard/images/symbols/empty.gif',
        line: 'templates/standard/theme/standard/images/symbols/line.svg',
        join: 'templates/standard/theme/standard/images/symbols/join.svg',
        joinBottom: 'templates/standard/theme/standard/images/symbols/join-bottom.svg',
        plus: 'templates/standard/theme/standard/images/symbols/plus-line.svg',
        plusBottom: 'templates/standard/theme/standard/images/symbols/plus-bottom.svg',
        minus: 'templates/standard/theme/standard/images/symbols/minus-line.svg',
        minusBottom: 'templates/standard/theme/standard/images/symbols/minus-bottom.svg',
        nlPlus: 'templates/standard/theme/standard/images/symbols/nolines_plus.gif',
        nlMinus: 'templates/standard/theme/standard/images/symbols/minus.svg'
    };
    this.obj = objName;
    this.allNodes = [];
    this.aIndent = [];
    this.root = new Node(-1);
    this.selectedNode = null;
    this.selectedFound = false;
    this.completed = false;
};

// Adds a new node to the node array
dTree.prototype.add = function (id, pid, name, url, title, target, icon, iconOpen, open, daysLeft) {
    this.allNodes[this.allNodes.length] = new Node(id, pid, name, url, title, target, icon, iconOpen, open, daysLeft);
};

// Open/close all nodes
dTree.prototype.openAll = function () {
    this.oAll(true);
};
dTree.prototype.closeAll = function () {
    this.oAll(false);
};

// Outputs the tree to the page
dTree.prototype.toString = function () {
    var str = '<div class="dtree">\n';
    str += this.addNode(this.root);
    str += '</div>';
    this.completed = true;
    return str;
};

// Creates the tree structure
dTree.prototype.addNode = function (pNode) {
    var str = '';
    var n = 0;
    if (this.config.inOrder) n = pNode._autoIncrement;
    for (n; n < this.allNodes.length; n++) {
        if (this.allNodes[n].pid == pNode.id) {
            var clonedNode = this.allNodes[n];
            clonedNode._parentNode = pNode;
            clonedNode._autoIncrement = n;
            this.setCS(clonedNode);
            if (!clonedNode.target && this.config.target) {
                clonedNode.target = this.config.target;
            }
            if (clonedNode._firstNode && !clonedNode._isOpen && this.config.useCookies) {
                clonedNode._isOpen = this.isOpen(clonedNode.id);
            }
            if (!this.config.folderLinks && clonedNode._firstNode) {
                clonedNode.url = null;
            }

            str += this.node(clonedNode, n);
            if (clonedNode._lastNode) break;
        }
    }
    return str;
};

// Creates the node icon, url and text
dTree.prototype.node = function (node, nodeId) {
    var str;
    str = '<div class="dTreeNode">' + this.indent(node, nodeId);
    if (this.config.useIcons) {
        if (!node.icon) {
            node.icon = (this.root.id == node.pid) ? this.icon.root : ((node._firstNode) ? this.icon.folder : this.icon.node);
        }
        if (!node.iconOpen) {
            node.iconOpen = (node._firstNode) ? this.icon.folderOpen : this.icon.node;
        }

        if (this.root.id == node.pid) {
            node.icon = this.icon.root;
            node.iconOpen = this.icon.root;
        }

        //dont display root node we dont need it
        if (this.root.id != node.pid) {
            str += '<img id="i' + this.obj + nodeId + '" src="' + ((node._isOpen) ? node.iconOpen : node.icon) + '" alt="" style = "height:27px;width:27px;" />';
        }
    }
    if (node.url) {
        str += '<a id="s' + this.obj + nodeId + '" class="' + ((this.config.useSelection) ? ((node._currentNode ? 'nodeSel' : 'node')) : 'node') + '" href="' + node.url + '"';
        if (node.title) {
            str += ' title="' + node.title + '"';
        }

        if (node.target) {
            str += ' target="' + node.target + '"';
        }

        if (typeof node.daysLeft == "number") {
            if (node.daysLeft < 0) {
                str += ' style = "color:#be4c43 "';
                //console.log(node.daysLeft);
            }
        }
        str += '>';
    }
    else if ((!this.config.folderLinks || !node.url) && node._firstNode && node.pid != this.root.id) {
        str += '<a href="javascript: ' + this.obj + '.o(' + nodeId + ');" class="node">';
    }

    str += node.name;

    if (node.url || ((!this.config.folderLinks || !node.url) && node._firstNode)) {
        str += '</a>';
    }

    str += '</div>';

    if (node._firstNode) {
        str += '<div id="d' + this.obj + nodeId + '" class="clip" style="display:' + ((this.root.id == node.pid || node._isOpen) ? 'block' : 'none') + ';">';
        str += this.addNode(node);
        str += '</div>';
    }
    this.aIndent.pop();
    return str;
};

// Adds the empty and line icons
dTree.prototype.indent = function (node, nodeId) {
    var str = '';
    if (this.root.id != node.pid) {
        for (var n = 0; n < this.aIndent.length; n++) {
            str += '<img src="' + ( (this.aIndent[n] == 1 && this.config.useLines) ? this.icon.line : this.icon.empty ) + '" alt="" style = "height:26px;width:26px;" />';
        }

        (node._lastNode) ? this.aIndent.push(0) : this.aIndent.push(1);
        if (node._firstNode) {
            str += '<a href="javascript: ' + this.obj + '.o(' + nodeId + ');"><img id="j' + this.obj + nodeId + '" src="';
            if (!this.config.useLines) {
                str += (node._isOpen) ? this.icon.nlMinus : this.icon.nlPlus;
            }
            else {
                str += ( (node._isOpen) ? ((node._lastNode && this.config.useLines) ? this.icon.minusBottom : this.icon.minus) : ((node._lastNode && this.config.useLines) ? this.icon.plusBottom : this.icon.plus ) );
            }

            str += '" alt="" style = "height:26px;width:26px;"  /></a>';
        } else {
            str += '<img src="' + ( (this.config.useLines) ? ((node._lastNode) ? this.icon.joinBottom : this.icon.join ) : this.icon.empty) + '" alt="" style = "height:26px;width:26px;" />';
        }
    }
    return str;
};

// Checks if a node has any children and if it is the last sibling
dTree.prototype.setCS = function (node) {
    var lastId;
    for (var n = 0; n < this.allNodes.length; n++) {
        if (this.allNodes[n].pid == node.id) {
            node._firstNode = true;
        }
        if (this.allNodes[n].pid == node.pid) {
            lastId = this.allNodes[n].id;
        }
    }
    if (lastId == node.id) {
        node._lastNode = true;
    }
};

// Toggle Open or close
dTree.prototype.o = function (id) {
    var cn = this.allNodes[id];
    this.nodeStatus(!cn._isOpen, id, cn._lastNode);
    cn._isOpen = !cn._isOpen;
    if (this.config.closeSameLevel) {
        this.closeLevel(cn);
    }
    if (this.config.useCookies){
        this.updateCookie();
    }
};

// Open or close all nodes
dTree.prototype.oAll = function (status) {
    for (var n = 0; n < this.allNodes.length; n++) {
        if (this.allNodes[n]._firstNode && this.allNodes[n].pid != this.root.id) {
            this.nodeStatus(status, n, this.allNodes[n]._lastNode);
            this.allNodes[n]._isOpen = status;
        }
    }
    if (this.config.useCookies) {
        this.updateCookie();
    }
};


// Opens the tree to a specific node
dTree.prototype.openTo = function (nId, bSelect, bFirst) {
    if (!bFirst) {
        for (var n = 0; n < this.allNodes.length; n++) {
            if (this.allNodes[n].id == nId) {
                nId = n;
                break;
            }
        }
    }
    var cn = this.allNodes[nId];
    if (cn.pid == this.root.id || !cn._parentNode){
        return;
    }
    cn._isOpen = true;
    cn._currentNode = bSelect;
    if (this.completed && cn._firstNode) {
        this.nodeStatus(true, cn._autoIncrement, cn._lastNode);
    }
    if (this.completed && bSelect) {
        this.s(cn._autoIncrement);
    }
    else if (bSelect) {
        this._sn = cn._autoIncrement;
    }
    this.openTo(cn._parentNode._autoIncrement, false, true);
};

// Closes all nodes on the same level as certain node
dTree.prototype.closeLevel = function (node) {
    for (var n = 0; n < this.allNodes.length; n++) {
        if (this.allNodes[n].pid == node.pid && this.allNodes[n].id != node.id && this.allNodes[n]._firstNode) {
            this.nodeStatus(false, n, this.allNodes[n]._lastNode);
            this.allNodes[n]._isOpen = false;
            this.closeAllChildren(this.allNodes[n]);
        }
    }
};

// Closes all children of a node
dTree.prototype.closeAllChildren = function (node) {
    for (var n = 0; n < this.allNodes.length; n++) {
        if (this.allNodes[n].pid == node.id && this.allNodes[n]._firstNode) {
            if (this.allNodes[n]._isOpen) {
                this.nodeStatus(false, n, this.allNodes[n]._lastNode);
            }
            this.allNodes[n]._isOpen = false;
            this.closeAllChildren(this.allNodes[n]);
        }
    }
};

// Change the status of a node(open or closed)
dTree.prototype.nodeStatus = function (status, id, bottom) {
    eDiv = document.getElementById('d' + this.obj + id);
    eJoin = document.getElementById('j' + this.obj + id);
    if (this.config.useIcons) {
        eIcon = document.getElementById('i' + this.obj + id);
        eIcon.src = (status) ? this.allNodes[id].iconOpen : this.allNodes[id].icon;
    }
    eJoin.src = (this.config.useLines) ?
        ((status) ? ((bottom) ? this.icon.minusBottom : this.icon.minus) : ((bottom) ? this.icon.plusBottom : this.icon.plus)) :
        ((status) ? this.icon.nlMinus : this.icon.nlPlus);
    eDiv.style.display = (status) ? 'block' : 'none';
};


// [Cookie] Clears a cookie
dTree.prototype.clearCookie = function () {
    var now = new Date();
    var yesterday = new Date(now.getTime() - 1000 * 60 * 60 * 24);
    this.setCookie('co' + this.obj, 'cookieValue', yesterday);
    this.setCookie('cs' + this.obj, 'cookieValue', yesterday);
};

// [Cookie] Sets value in a cookie
dTree.prototype.setCookie = function (cookieName, cookieValue, expires, path, domain, secure) {
    document.cookie =
        escape(cookieName) + '=' + escape(cookieValue)
        + (expires ? '; expires=' + expires.toGMTString() : '')
        + (path ? '; path=' + path : '')
        + (domain ? '; domain=' + domain : '')
        + (secure ? '; secure' : '');
};

// [Cookie] Gets a value from a cookie
dTree.prototype.getCookie = function (cookieName) {
    var cookieValue = '';
    var posName = document.cookie.indexOf(escape(cookieName) + '=');
    if (posName != -1) {
        var posValue = posName + (escape(cookieName) + '=').length;
        var endPos = document.cookie.indexOf(';', posValue);
        if (endPos != -1) cookieValue = unescape(document.cookie.substring(posValue, endPos));
        else cookieValue = unescape(document.cookie.substring(posValue));
    }
    return (cookieValue);
};

// [Cookie] Returns ids of open nodes as a string
dTree.prototype.updateCookie = function () {
    var str = '';
    for (var n = 0; n < this.allNodes.length; n++) {
        if (this.allNodes[n]._isOpen && this.allNodes[n].pid != this.root.id) {
            if (str) {
                str += '.';
            }
            str += this.allNodes[n].id;
        }
    }
    this.setCookie('co' + this.obj, str);
};

// [Cookie] Checks if a node id is in a cookie
dTree.prototype.isOpen = function (id) {
    var aOpen = this.getCookie('co' + this.obj).split('.');
    for (var n = 0; n < aOpen.length; n++) {
        if (aOpen[n] == id) {
            return true;
        }
    }
    return false;
};


// If Push and pop is not implemented by the browser
if (!Array.prototype.push) {
    Array.prototype.push = function array_push() {
        for (var i = 0; i < arguments.length; i++){
            this[this.length] = arguments[i];
        }
        return this.length;
    }
}

if (!Array.prototype.pop) {
    Array.prototype.pop = function array_pop() {
        lastElement = this[this.length - 1];
        this.length = Math.max(this.length - 1, 0);
        return lastElement;
    }
}
