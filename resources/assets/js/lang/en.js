export default {
  route: {
    dashboard: 'Dashboard',
    introduction: 'Introduction',
    documentation: 'Documentation',
    guide: 'Guide',
    permission: 'Permission',
    pagePermission: 'Page Permission',
    directivePermission: 'Directive Permission',
    icons: 'Icons',
    components: 'Components',
    componentIndex: 'Introduction',
    tinymce: 'Tinymce',
    markdown: 'Markdown',
    jsonEditor: 'JSON Editor',
    dndList: 'Dnd List',
    splitPane: 'SplitPane',
    avatarUpload: 'Avatar Upload',
    dropzone: 'Dropzone',
    sticky: 'Sticky',
    countTo: 'CountTo',
    componentMixin: 'Mixin',
    backToTop: 'BackToTop',
    dragDialog: 'Drag Dialog',
    dragKanban: 'Drag Kanban',
    charts: 'Charts',
    keyboardChart: 'Keyboard Chart',
    lineChart: 'Line Chart',
    mixChart: 'Mix Chart',
    example: 'Example',
    Table: 'Table',
    dynamicTable: 'Dynamic Table',
    dragTable: 'Drag Table',
    inlineEditTable: 'Inline Edit',
    complexTable: 'Complex Table',
    treeTable: 'Tree Table',
    customTreeTable: 'Custom TreeTable',
    tab: 'Tab',
    form: 'Form',
    createForm: 'Create Form',
    editForm: 'Edit Form',
    errorPages: 'Error Pages',
    page401: '401',
    page404: '404',
    errorLog: 'Error Log',
    excel: 'Excel',
    exportExcel: 'Export Excel',
    selectExcel: 'Export Selected',
    uploadExcel: 'Upload Excel',
    zip: 'Zip',
    exportZip: 'Export Zip',
    theme: 'Theme',
    clipboardDemo: 'Clipboard',
    i18n: 'I18n'
  },
  auth:{
    login: 'Login',
    register: 'Register',
    backTo: 'Back To',
    passwordPlaceholder: 'Please input password',
    namePlaceholder: 'user name/phone',
    loading: 'Loading',
    name: 'Name',
    password: 'Password',
    step: 'Step',
    phonePlaceholder:'Please input phone',
    captchaCode:'Please input captcha code',
    captchaTitle:'Click image to refresh code',
    captchaGot:'Captcha Code',
    usernamePlaceholder:'Please input name',
    emailPlaceholder:'Please input email',
    back:'back',
    next:'Next',

  },
  navbar: {
    logOut: 'Log Out',
    dashboard: 'Dashboard',
    github: 'Github',
    screenfull: 'screenfull',
    theme: 'theme',
    profile:'profile'
  },
  login: {
    title: 'Login Form',
    logIn: 'Log in',
    username: 'Username',
    password: 'Password',
    any: 'any',
    thirdparty: 'Or connect with',
    thirdpartyTips: 'Can not be simulated on local, so please combine you own business simulation! ! !'
  },
  documentation: {
    documentation: 'Documentation',
    github: 'Github Repository'
  },
  permission: {
    roles: 'Your roles',
    switchRoles: 'Switch roles'
  },
  guide: {
    description: 'The guide page is useful for some people who entered the project for the first time. You can briefly introduce the features of the project. Demo is based on ',
    button: 'Show Guide'
  },
  components: {
    documentation: 'Documentation',
    tinymceTips: 'Rich text editor is a core part of management system, but at the same time is a place with lots of problems. In the process of selecting rich texts, I also walked a lot of detours. The common rich text editors in the market are basically used, and the finally chose Tinymce. See documentation for more detailed rich text editor comparisons and introductions.',
    dropzoneTips: 'Because my business has special needs, and has to upload images to qiniu, so instead of a third party, I chose encapsulate it by myself. It is very simple, you can see the detail code in @/components/Dropzone.',
    stickyTips: 'when the page is scrolled to the preset position will be sticky on the top.',
    backToTopTips1: 'When the page is scrolled to the specified position, the Back to Top button appears in the lower right corner',
    backToTopTips2: 'You can customize the style of the button, show / hide, height of appearance, height of the return. If you need a text prompt, you can use element-ui el-tooltip elements externally',
    imageUploadTips: 'Since I was using only the vue@1 version, and it is not compatible with mockjs at the moment, I modified it myself, and if you are going to use it, it is better to use official version.'
  },
  table: {
    dynamicTips1: 'Fixed header, sorted by header order',
    dynamicTips2: 'Not fixed header, sorted by click order',
    dragTips1: 'The default order',
    dragTips2: 'The after dragging order',
    title: 'Title',
    version: 'Version',
    importance: 'Imp',
    type: 'Type',
    remark: 'Remark',
    search: 'Search',
    add: 'Add',
    export: 'Export',
    reviewer: 'reviewer',
    id: 'ID',
    iccid: 'Iccid',
    status:'Status',
    name: 'Name',
    lat: 'Lattitude',
    lon: 'Longitude',
    normal: 'Normal',
    abnormal: 'Abnormal',
    date: 'Date',
    author: 'Author',
    readings: 'Readings',
    status: 'Status',
    actions: 'Actions',
    edit: 'Edit',
    publish: 'Publish',
    draft: 'Draft',
    delete: 'Delete',
    cancel: 'Cancel',
    confirm: 'Confirm',
    detach: 'Dettach',
    attach: 'Attach',
    editConfig:  'Setting',
    detail: 'Detail',
    addsub: 'add Sub-Org',
    description: 'Description',
    create:'Create',
    loading:'Loading',
  },
  device:{
    config: 'Config',
    index: 'Information',
    datas: 'Data',
    gallery: 'Image',
    weather:'Weather',
    record: 'Record',
    attach: 'Attach',
    attachDescription: 'Please input the SN number of atteched device'
  },
  config:{
    edit: 'Edit',
    confirm: 'Confirm',
    cancel: 'Cancel',
    actions: 'Actions',
    sensorType: 'Sensor',
    dataType:'Param',
    port: 'Port',
    add: 'Add',
    save:'Save',
    version:'Version',
    name:'Name',
    key:'Key',
    desc:'Description',
    titleImageCtrl:'Image Upload Interval',
    titleDataCtrl:'Data Upload Interval',
  },
  profile:{
    invitation:'Invitation',
    account:'Account',
    invitation:'Invitation',
    invitedOwner:'Owner',
    invitedUser:'User',
    invitedRole: 'Role',
    device:'Device',
    organization:'Organization',
    status: 'Status',
    operation: 'Operation',
    pass:'Pass',
    unpass:'UnPass',
    pending:'Pending',
    delete:'Delete',
  },
  org:{
    add: 'Add',
    detach:'Detach',
    details:'Detail',
    addsub:'+Sub',
    device:'Device',
    people:'People',
    setting:'Setting',
    name:'Name',
    description:'Description',
    update:'Update',
    inviteUser:'Invite User',
    inviteTitle:'Input user for invitation',
    inviteType:'User\'s',
    inviteTypeName:'Name',
    inviteTypeEmail:'Email',
    inviteTypePhone:'Phone',
    input:'Input...',
    removePeopleTitle:'Remove the user from current organization?',
  },
  account:{
    oldPassword: "Password",
    newPassword: "New Password",
    confirmPassword: "Confirm Password",
    resetPassword:"Reset Password",
    update:"Update",
    changeInfo:"Change User Information",
    changeName:"Change User Name",
    oldPasswordInfo:'Please input old password',
  },
  errorLog: {
    tips: 'Please click the bug icon in the upper right corner',
    description: 'Now the management system are basically the form of the spa, it enhances the user experience, but it also increases the possibility of page problems, a small negligence may lead to the entire page deadlock. Fortunately Vue provides a way to catch handling exceptions, where you can handle errors or report exceptions.',
    documentation: 'Document introduction'
  },
  excel: {
    export: 'Export',
    selectedExport: 'Export selected items',
    placeholder: 'Please enter the file name(default excel-list)'
  },
  zip: {
    export: 'Export',
    placeholder: 'Please enter the file name(default file)'
  },
  theme: {
    change: 'Theme change',
    documentation: 'Theme documentation',
    tips: 'Tips: It is different from the theme-pick on the navbar is two different skinning methods, each with different application scenarios. Refer to the documentation for details.'
  },
  tagsView: {
    close: 'Close',
    closeOthers: 'Close Others',
    closeAll: 'Close All'
  },
  data:{
    device:"Device",
    to: "to",
    date:"Date",
    startDate: "Start Date",
    endDate: "End Date",
    organization:"Organization",
    date:"Date",
    devicePalceholder:"Please select devices for Download",
    execute:"Download",
    image:"Image",
    data:"Data",
    del:"Delete",
    status: 'Status',
    created_at:'Created at',
    id:"ID",
    processing:"Processing",
    completed:"Completed",
    devicePlaceholder:"Selete devices for download",
    content:"Contents",
    operation:"Operation",
    refresh:"Refresh",
    detailContent:"Data Type",
    detailExecute:"Load",
    contentPlaceholder:"Please select data type"
  },
  success:{
    title:"Success",
    config:"Device config OK!",
    deviceRefresh:"Device Refresh OK!",
    removePeople:"Remove People OK!",
    invitePeople:'Inivitation succcess, wait for the acception',
    refresh:'Refresh OK!'
  },
  error:{
    date:"Please input date",
    dateRange:"The range needs less than 30 days",
    device:"Select device",
    content:"Select content",
    auth:'name or password error!',
    server:'server inner error, try again',
    title:'Error',
    default:'Ooh, error, try again!',
    password:'Please input password',
    confirmPassword:'Please confirm password',
    diffrentPassword:'Passwords are different',
  }
}
